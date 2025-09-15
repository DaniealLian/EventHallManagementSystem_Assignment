<?php

namespace App\Strategies;

use App\Models\Reservation;

class EWalletPayment
{
    public function pay(Reservation $reservation, float $amount): string
    {
        // Simulated e-wallet payment
        if ($amount > 0) {
            return 'success';
        }

        return 'Failed';
    }
}