<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Strategies\PaymentContext;
use App\Strategies\CardPayment;
use App\Strategies\OnlineBankingPayment;
use App\Strategies\EWalletPayment;

class PaymentController extends Controller
{
    public function checkout(Reservation $reservation){
        $reservation->load(['event', 'reservationItems.pricingTier']);

        return view('payments.checkout', compact('reservation'));
    }

    public function process(Request $request)
    {
        // ✅ 1. Validate input
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'amount' => 'required|numeric|min:1',
            'method' => 'required|in:credit_card,debit_card,paypal',
        ]);

        // ✅ 2. Find reservation
        $reservation = Reservation::findOrFail($validated['reservation_id']);

        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized to process payment for this reservation.');
        }

        // ✅ 3. Pick strategy based on method
        $strategy = match ($validated['method']) {
            'credit_card' => new CardPayment(),
            'debit_card'  => new CardPayment(), // you can differentiate later if needed
            'paypal'      => new EWalletPayment(),
            'online_banking' => new OnlineBankingPayment(),
            default       => throw new \Exception("Unsupported payment method"),
        };

        // ✅ 4. Process payment using Strategy Pattern
        $paymentContext = new PaymentContext($strategy);
        $status = $paymentContext->pay($reservation, $validated['amount']); 
        // status will be "success", "pending", or "failed"

        // ✅ 5. Save record
        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'amount' => $validated['amount'],
            'method' => $validated['method'],
            'status' => $status,
            'gateway' => $this->getGatewayName($validated['method']),
            'gateway_transaction_id' => uniqid("txn_"), // just a sample transaction ID
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // ✅ 6. Handle status and redirect with proper messages
        return match ($status) {
            'success' => redirect()->route('payments.status')
                                   ->with('success', 'Payment completed successfully! Transaction ID: ' . $payment->transaction_id),

            'pending' => redirect()->route('payments.status')
                                   ->with('info', 'Payment is pending confirmation. Please check later.'),

            'failed'  => redirect()->route('payments.status')
                                   ->with('error', 'Payment failed. Please try again.'),

            default   => redirect()->route('payments.status')
                                   ->with('error', 'Unexpected payment status.')
        };
    }

    private function getGatewayName(string $method): string
    {
        return match ($method) {
            'credit_card', 'debit_card' => 'stripe', // or whatever you're using
            'paypal' => 'paypal',
            'online_banking' => 'fpx',
            default => 'manual'
        };
    }
}