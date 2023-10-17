<?php

declare(strict_types=1);

namespace App\Payment;

use App\CalculatePrice\PriceCalculator;
use App\DTO\PurchaseDTO;
use App\TaxNumber\TaxNumberService;

class PaymentProcessor
{
    public function __construct(
        private readonly PaymentProcessorFactory $processorFactory,
        private readonly PriceCalculator $priceCalculator,
        private readonly TaxNumberService $taxNumberService
    )
    {
    }

    public function process(PurchaseDTO $data): void
    {
        $codeCountry = $this->taxNumberService->handle($data->taxNumber);
        $totalPrice = $this->priceCalculator->calculateTotalPrice($codeCountry, $data->product, $data->couponCode);
        $paymentProcessor = $this->processorFactory->createPaymentProcessor($data->paymentProcessor);

        $paymentProcessor->pay($totalPrice);
    }
}