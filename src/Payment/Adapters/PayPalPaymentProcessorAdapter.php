<?php

namespace App\Payment\Adapters;

use App\Exceptions\PaymentProcessorException;
use App\Payment\PaymentInterface;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class PayPalPaymentProcessorAdapter implements PaymentInterface
{
    /**
     * @param PaypalPaymentProcessor $processor
     */
    public function __construct(private readonly PaypalPaymentProcessor $processor)
    {
    }


    public function pay(float $amount): void
    {
        try {
            $this->processor->pay((int)$amount);
        } catch (Exception $e) {
            throw new PaymentProcessorException();
        }
    }
}