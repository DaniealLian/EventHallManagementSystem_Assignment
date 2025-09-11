<?php

namespace App\Strategies;

use App\Models\Reservation;

class PaymentContext
{
    protected $strategy;

    public function __construct($strategy)
    {
        $this->strategy = $strategy;
    }

    public function pay(Reservation $reservation, float $amount): string
    {
        return $this->strategy->pay($reservation, $amount);
    }
}
