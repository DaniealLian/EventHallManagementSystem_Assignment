<?php

namespace App\Services\Payments;

use App\Contracts\PaymentStrategy;

class PaymentContext
{
    protected PaymentStrategy $paymentStrategy;

    /**
     * Set the payment strategy dynamically
     *
     * @param PaymentStrategy $strategy
     */
    public function setStrategy(PaymentStrategy $strategy)
    {
        $this->paymentStrategy = $strategy;
    }

    /**
     * Process the payment using the selected strategy
     *
     * @param float $amount
     * @param array $details
     * @return mixed
     */
    public function pay(float $amount, array $details)
    {
        if (!isset($this->paymentStrategy)) {
            throw new \Exception("No payment strategy selected.");
        }

        return $this->paymentStrategy->pay($amount, $details);
    }
}
