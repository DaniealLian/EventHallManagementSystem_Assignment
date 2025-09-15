<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Strategies\PaymentContext;
use App\Strategies\CardPayment;
use App\Strategies\OnlineBankingPayment;
use App\Strategies\EWalletPayment;
use App\Services\ReservationSessionService;

class PaymentController extends Controller
{

    //----------------For deleting reservation data--
    protected $sessionService;  

    public function __construct(ReservationSessionService $sessionService)
    {
        $this->sessionService = $sessionService;  
    }

     public function cancelPayment(Reservation $reservation)
    {
        try {
           
                $this->sessionService->cancelledSlots($reservation);
                
                $reservation->delete();
            
                return redirect()
                    ->route('reservations.index')
                    ->with('success', 'Payment cancelled and tickets released back to inventory.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Failed to cancel reservation: ' . $e->getMessage());
        }
    }
    //-----------------------------------------------



    public function checkout(Reservation $reservation){
        $reservation->load(['event', 'reservationItems.pricingTier']);

        return view('payments.checkout', compact('reservation'));
    }

    public function process(Request $request)
    {
        // ✅ 1. Validate input - Updated validation rules to match form values
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'method' => 'required|in:card,online_banking,e_wallet', // Updated to match form options
        ]);

        // ✅ 2. Find reservation
        $reservation = Reservation::findOrFail($validated['reservation_id']);

        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to process payment for this reservation.');
        }

        // Use reservation total_price as amount
        $amount = $reservation->total_price;

        // ✅ 3. Pick strategy based on method - Updated to match form values
        $strategy = match ($validated['method']) {
            'card' => new CardPayment(),
            'online_banking' => new OnlineBankingPayment(),
            'e_wallet' => new EWalletPayment(),
            default => throw new \Exception("Unsupported payment method"),
        };

        // ✅ 4. Process payment using Strategy Pattern
        $paymentContext = new PaymentContext($strategy);
        $status = $paymentContext->pay($reservation, $amount);

        // ✅ 5. Save record
        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $amount,
            'method' => $validated['method'],
            'status' => $status,
            'gateway' => $this->getGatewayName($validated['method']),
            'gateway_transaction_id' => uniqid("txn_"),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ 6. Handle status and redirect with proper messages
        return match ($status) {
            'success' => redirect()->route('payments.status')
                                   ->with('success', 'Payment completed successfully! Transaction ID: ' . $payment->gateway_transaction_id),

            'pending' => redirect()->route('payments.status')
                                   ->with('info', 'Payment is pending confirmation. Please check later.'),

            'failed'  => redirect()->route('payments.status')
                                   ->with('error', 'Payment failed. Please try again.'),

            default   => redirect()->route('payments.status')
                                   ->with('error', 'Unexpected payment status.')
        };
    }

    public function status()
    {
        return view('payments.status');
    }

    private function getGatewayName(string $method): string
    {
        return match ($method) {
            'card' => 'stripe',
            'e_wallet' => 'paypal',
            'online_banking' => 'fpx',
            default => 'manual'
        };
    }

   
}
