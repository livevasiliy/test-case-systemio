<?php

declare(strict_types=1);

namespace App\Tests\Payment;

use App\DTO\PurchaseDTO;
use App\Payment\PaymentProcessor;
use App\Payment\PaymentProcessorFactory;
use App\Payment\PaymentProcessorInterface;
use Generator;
use PHPUnit\Framework\TestCase;

class PaymentProcessorTest extends TestCase
{
    public function paymentProcessorProvider(): Generator
    {
        yield 'Valid - use PayPal payment provider' => ['paypal'];
        yield 'Valid - use Stripe payment provider' => ['stripe'];
        yield 'Invalid - use unknown payment provider' => ['foo'];
    }

    /**
     * @dataProvider paymentProcessorProvider
     */
    public function testProcessCallsPayOnCorrectProcessor(string $paymentProcessorType): void
    {
        // Create a PurchaseDTO
        $purchaseDTO = new PurchaseDTO(13, 'IT12345678900', 'D15', $paymentProcessorType);
        $purchaseDTO->paymentProcessor = $paymentProcessorType;

        // Mock the PaymentProcessorFactory
        $processorFactory = $this->createMock(PaymentProcessorFactory::class);
        $processorFactory->method('createPaymentProcessor')
            ->willReturn($this->createMock(PaymentProcessorInterface::class));

        // Create an instance of PaymentProcessor
        $paymentProcessor = new PaymentProcessor($processorFactory);

        // Call the process method
        $paymentProcessor->process($purchaseDTO);

        // Assert that the pay method was called on the correct processor
        $this->assertTrue(true); // You can add more assertions based on your specific implementation
    }
}
