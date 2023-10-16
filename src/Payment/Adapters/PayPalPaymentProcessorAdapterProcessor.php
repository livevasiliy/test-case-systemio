<?php

declare(strict_types=1);

namespace App\Payment\Adapters;

use App\Exceptions\PaymentProcessorException;
use App\Payment\PaymentProcessorInterface;
use Exception;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PayPalPaymentProcessorAdapterProcessor implements PaymentProcessorInterface
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
            throw new PaymentProcessorException($e->getMessage());
        }
    }
}