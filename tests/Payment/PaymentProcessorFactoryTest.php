<?php

declare(strict_types=1);

namespace App\Tests\Payment;

use App\Exceptions\PaymentProcessorException;
use App\Payment\Adapters\PayPalPaymentProcessorAdapterProcessor;
use App\Payment\Adapters\StripePaymentProcessorAdapterProcessor;
use App\Payment\PaymentProcessorFactory;
use PHPUnit\Framework\TestCase;

class PaymentProcessorFactoryTest extends TestCase
{
    public function testCreatePaymentProcessorReturnsPayPalProcessor(): void
    {
        $payPalProcessor = $this->createMock(PayPalPaymentProcessorAdapterProcessor::class);
        $stripeProcessor = $this->createMock(StripePaymentProcessorAdapterProcessor::class);

        $factory = new PaymentProcessorFactory($payPalProcessor, $stripeProcessor);

        $result = $factory->createPaymentProcessor('paypal');

        $this->assertInstanceOf(PayPalPaymentProcessorAdapterProcessor::class, $result);
    }

    public function testCreatePaymentProcessorReturnsStripeProcessor(): void
    {
        $payPalProcessor = $this->createMock(PayPalPaymentProcessorAdapterProcessor::class);
        $stripeProcessor = $this->createMock(StripePaymentProcessorAdapterProcessor::class);

        $factory = new PaymentProcessorFactory($payPalProcessor, $stripeProcessor);

        $result = $factory->createPaymentProcessor('stripe');

        $this->assertInstanceOf(StripePaymentProcessorAdapterProcessor::class, $result);
    }

    public function testCreatePaymentProcessorThrowsExceptionForUnknownMethod(): void
    {
        $payPalProcessor = $this->createMock(PayPalPaymentProcessorAdapterProcessor::class);
        $stripeProcessor = $this->createMock(StripePaymentProcessorAdapterProcessor::class);

        $factory = new PaymentProcessorFactory($payPalProcessor, $stripeProcessor);

        $this->expectException(PaymentProcessorException::class);
        $factory->createPaymentProcessor('unknown_method');
    }
}
