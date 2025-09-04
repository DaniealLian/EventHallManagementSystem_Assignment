<?php

namespace App\Services\Payments;

use App\Contracts\PaymentStrategy;

class CardPayment implements PaymentStrategy
{
    /**
     * Handle payment using Credit/Debit Card
     *
     * @param float $amount
     * @param array $details
     * @return string
     */
    public function pay(float $amount, array $details)
    {
        // Example: simulate card details validation
        if (empty($details['card_number']) || empty($details['card_holder'])) {
            return "❌ Payment failed: Missing card details.";
        }

        // Normally here you would integrate with a payment gateway (e.g., Stripe, PayPal)
        // For now we just simulate the process
        return "✅ Payment of RM{$amount} completed successfully via Card (" . substr($details['card_number'], -4) . ").";
    }
}
