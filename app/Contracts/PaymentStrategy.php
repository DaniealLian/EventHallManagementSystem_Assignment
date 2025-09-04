<?php

namespace App\Contracts;

interface PaymentStrategy
{
    /**
     * Execute the payment process.
     *
     * @param float $amount   The amount to be paid.
     * @param array $details  Additional payment details (card info, account, etc.)
     * @return mixed
     */
    public function pay(float $amount, array $details);
}
