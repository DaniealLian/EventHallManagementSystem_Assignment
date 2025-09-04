<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\Payments\PaymentContext;
use App\Services\Payments\CardPayment;
use App\Services\Payments\EWalletPayment;
use App\Services\Payments\OnlineBankingPayment;
use App\Models\Transaction;

class PaymentController extends Controller
{
    /**
     * Show payment method selection page
     */
    public function index()
    {
        return view('payment.index');
    }

    /**
     * Show checkout form for chosen method
     */
    public function checkoutForm(Request $request)
    {
        $method = $request->input('method');
        return view('payment.checkout', compact('method'));
    }

    /**
     * Process the payment
     */
    public function checkout(Request $request)
    {
        $method = $request->input('method'); // card, ewallet, onlinebanking
        $amount = $request->input('amount');
        $details = $request->all();

        $context = new PaymentContext();

        // Select strategy based on user choice
        switch ($method) {
            case 'card':
                $context->setStrategy(new CardPayment());
                break;
            case 'ewallet':
                $context->setStrategy(new EWalletPayment());
                break;
            case 'onlinebanking':
                $context->setStrategy(new OnlineBankingPayment());
                break;
            default:
                return back()->with('error', 'Invalid payment method selected.');
        }

        // Execute payment
        $result = $context->pay($amount, $details);

        // Save transaction in database
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'event_id' => $request->input('event_id'),
            'amount' => $amount,
            'method' => $method,
            'status' => str_contains($result, '✅') ? 'paid' : 'failed',
        ]);

        return view('payment.status', compact('result', 'transaction'));
    }

    /**
     * Show transaction status
     */
    public function status($id)
    {
        $transaction = Transaction::findOrFail($id);
        return view('payment.status', compact('transaction'));
    }
}