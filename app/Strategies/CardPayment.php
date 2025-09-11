<?php

namespace App\Strategies;

use App\Models\Reservation;

class CardPayment
{
    /**
     * Process card payment for a reservation
     */
    public function pay(Reservation $reservation, float $amount): string
    {
        // ✅ Secure coding: NEVER store card details directly, only tokens.
        // Simulated example of card processing:
        if ($amount > 0) {
            // In real life, call a payment gateway API here (e.g., Stripe, PayPal)
            return 'Paid'; 
        }

        return 'Failed';
    }
}
