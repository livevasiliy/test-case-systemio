<?php

declare(strict_types=1);

namespace App\Tests\Payment\Adapters;

use App\Exceptions\PaymentProcessorException;
use App\Payment\Adapters\PayPalPaymentProcessorAdapterProcessor;
use Exception;
use PHPUnit\Framework\TestCase;
use Systemeio\TestForCandidates\PaymentProcessor\PaypalPaymentProcessor;

class PayPalPaymentProcessorAdapterProcessorTest extends TestCase
{
    public function testPayCallsProcessorWithCorrectAmount(): void
    {
        // Mock the PaypalPaymentProcessor
        $paypalPaymentProcessor = $this->createMock(PaypalPaymentProcessor::class);

        // Create an instance of PayPalPaymentProcessorAdapterProcessor
        $adapter = new PayPalPaymentProcessorAdapterProcessor($paypalPaymentProcessor);

        // Define the amount
        $amount = 100.0;

        // Expect the pay method to be called with the correct amount
        $paypalPaymentProcessor->expects($this->once())
            ->method('pay')
            ->with($amount);

        // Call the pay method
        $adapter->pay($amount);
    }

    public function testPayThrowsExceptionOnProcessorException(): void
    {
        // Mock the PaypalPaymentProcessor to throw an exception
        $paypalPaymentProcessor = $this->createMock(PaypalPaymentProcessor::class);
        $paypalPaymentProcessor->method('pay')
            ->willThrowException(new Exception('Payment failed'));

        // Create an instance of PayPalPaymentProcessorAdapterProcessor
        $adapter = new PayPalPaymentProcessorAdapterProcessor($paypalPaymentProcessor);

        // Define the amount
        $amount = 100.0;

        // Expect the PaymentProcessorException to be thrown
        $this->expectException(PaymentProcessorException::class);

        // Call the pay method
        $adapter->pay($amount);
    }
}
