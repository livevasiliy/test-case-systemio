<?php

declare(strict_types=1);

namespace App\Tests\Payment\Adapters;

use App\Exceptions\PaymentProcessorException;
use App\Payment\Adapters\StripePaymentProcessorAdapterProcessor;
use App\Payment\PaymentProcessorInterface;
use Exception;
use PHPUnit\Framework\TestCase;
use Systemeio\TestForCandidates\PaymentProcessor\StripePaymentProcessor;

class StripePaymentProcessorAdapterProcessorTest extends TestCase
{
    public function testPayCallsProcessorWithCorrectAmount(): void
    {
        // Mock the StripePaymentProcessor
        $stripePaymentProcessor = $this->createMock(StripePaymentProcessor::class);

        // Create an instance of StripePaymentProcessorAdapterProcessor
        $adapter = new StripePaymentProcessorAdapterProcessor($stripePaymentProcessor);

        // Define the amount
        $amount = 100.0;

        // Expect the processPayment method to be called with the correct amount
        $stripePaymentProcessor->expects($this->once())
            ->method('processPayment')
            ->with($amount)
            ->willReturn(true);

        // Call the pay method
        $adapter->pay($amount);
    }

    public function testPayThrowsExceptionOnFailedPayment(): void
    {
        // Mock the StripePaymentProcessor to return false for processPayment
        $stripePaymentProcessor = $this->createMock(StripePaymentProcessor::class);
        $stripePaymentProcessor->method('processPayment')
            ->willReturn(false);

        // Create an instance of StripePaymentProcessorAdapterProcessor
        $adapter = new StripePaymentProcessorAdapterProcessor($stripePaymentProcessor);

        // Define the amount
        $amount = 100.0;

        // Expect the PaymentProcessorException to be thrown
        $this->expectException(PaymentProcessorException::class);

        // Call the pay method
        $adapter->pay($amount);
    }

    public function testPayThrowsExceptionOnException(): void
    {
        // Mock the StripePaymentProcessor to throw an exception
        $stripePaymentProcessor = $this->createMock(StripePaymentProcessor::class);
        $stripePaymentProcessor->method('processPayment')
            ->willThrowException(new Exception('Payment processing failed'));

        // Create an instance of StripePaymentProcessorAdapterProcessor
        $adapter = new StripePaymentProcessorAdapterProcessor($stripePaymentProcessor);

        // Define the amount
        $amount = 100.0;

        // Expect the PaymentProcessorException to be thrown
        $this->expectException(PaymentProcessorException::class);

        // Call the pay method
        $adapter->pay($amount);
    }
}
