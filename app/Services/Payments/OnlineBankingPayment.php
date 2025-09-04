<?php

namespace App\Services\Payments;

use App\Contracts\PaymentStrategy;

class OnlineBankingPayment implements PaymentStrategy
{
    /**
     * Handle payment using Online Banking
     *
     * @param float $amount
     * @param array $details
     * @return string
     */
    public function pay(float $amount, array $details)
    {
        // Example: validate online banking details
        if (empty($details['bank_name']) || empty($details['account_number'])) {
            return "❌ Payment failed: Missing bank details.";
        }

        // Normally you would integrate with FPX or bank's API here
        // For now we simulate the process
        return "✅ Payment of RM{$amount} completed successfully via Online Banking ({$details['bank_name']}).";
    }
}
