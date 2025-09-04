<?php

namespace App\Services\Payments;

use App\Contracts\PaymentStrategy;

class EWalletPayment implements PaymentStrategy
{
    /**
     * Handle payment using E-Wallet
     *
     * @param float $amount
     * @param array $details
     * @return string
     */
    public function pay(float $amount, array $details)
    {
        // Example: simulate e-wallet validation
        if (empty($details['wallet_id'])) {
            return "❌ Payment failed: Missing e-wallet ID.";
        }

        // Normally here you would integrate with e-wallet provider (e.g., GrabPay, Touch 'n Go, Boost)
        // For now we just simulate the process
        return "✅ Payment of RM{$amount} completed successfully via E-Wallet (ID: {$details['wallet_id']}).";
    }
}
