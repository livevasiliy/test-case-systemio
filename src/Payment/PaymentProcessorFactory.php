<?php

declare(strict_types=1);

namespace App\Payment;

use App\Exceptions\PaymentProcessorException;
use App\Payment\Adapters\PayPalPaymentProcessorAdapterProcessor;
use App\Payment\Adapters\StripePaymentProcessorAdapterProcessor;

class PaymentProcessorFactory
{
    public function __construct(
        private PayPalPaymentProcessorAdapterProcessor $paypalPaymentProcessor,
        private StripePaymentProcessorAdapterProcessor $stripePaymentProcessor
    ) {
    }

    public function createPaymentProcessor($paymentMethod): PaymentProcessorInterface
    {
        return match ($paymentMethod) {
            'paypal' => $this->paypalPaymentProcessor,
            'stripe' => $this->stripePaymentProcessor,
            default => throw new PaymentProcessorException('Unknown payment method: ' . $paymentMethod),
        };
    }
}
