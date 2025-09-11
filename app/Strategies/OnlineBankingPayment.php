<?php

namespace App\Strategies;

use App\Models\Reservation;

class OnlineBankingPayment
{
    public function pay(Reservation $reservation, float $amount): string
    {
        // Simulated online banking logic
        if ($amount > 0) {
            return 'Pending'; // Bank transfers may not be instant
        }

        return 'Failed';
    }
}