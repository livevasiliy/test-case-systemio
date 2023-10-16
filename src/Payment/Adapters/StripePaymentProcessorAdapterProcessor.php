<?php

declare(strict_types=1);

namespace App\Payment\Adapters;

use App\Exceptions\PaymentProcessorException;
use App\Payment\PaymentProcessorInterface;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapterProcessor implements PaymentProcessorInterface
{
    /**
     * @param StripePaymentProcessor $processor
     */
    public function __construct(private readonly StripePaymentProcessor $processor)
    {
    }

    public function pay(float $amount): void
    {
        try {
            if (!$this->processor->processPayment($amount)) {
                throw new PaymentProcessorException();
            }
        } catch (Exception $exception) {
            throw new PaymentProcessorException($exception->getMessage());
        }

    }
}