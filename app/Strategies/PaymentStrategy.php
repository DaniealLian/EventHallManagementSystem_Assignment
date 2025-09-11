<?php

namespace App\Strategies;

use App\Models\Reservation;

interface PaymentStrategy
{
    public function pay(Reservation $reservation, float $amount): string;
}
