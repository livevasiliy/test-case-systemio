<?php

declare(strict_types=1);

namespace App\Payment;

use App\CalculatePrice\CalculatePriceWorkflow;
use App\DTO\PurchaseDTO;

class PaymentProcessor
{
    public function __construct(
        private readonly PaymentProcessorFactory $processorFactory
    )
    {
    }

    public function process(PurchaseDTO $data): void
    {


        $paymentProcessor = $this->processorFactory->createPaymentProcessor($data->paymentProcessor);

        $paymentProcessor->pay(2500);
    }
}