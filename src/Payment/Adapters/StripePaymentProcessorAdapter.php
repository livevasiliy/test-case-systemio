<?php

namespace App\Payment\Adapters;

use App\Exceptions\PaymentProcessorException;
use App\Payment\PaymentInterface;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapter implements PaymentInterface
{
    /**
     * @param StripePaymentProcessor $processor
     */
    public function __construct(private readonly StripePaymentProcessor $processor)
    {
    }

    public function pay(float $amount): void
    {
        if (!$this->processor->processPayment($amount)) {
            throw new PaymentProcessorException();
        }
    }
}