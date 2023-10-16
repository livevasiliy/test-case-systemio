<?php

declare(strict_types=1);

namespace App\CalculatePrice;

use App\DTO\CalculatePriceDTO;
use App\TaxNumber\TaxNumberService;

class CalculatePriceWorkflow
{
    public function __construct(
        private readonly PriceCalculator $priceCalculator,
        private readonly TaxNumberService $taxNumberService
    )
    {
    }

    public function calculatePrice(CalculatePriceDTO $data): float
    {
        $codeCountry = $this->taxNumberService->handle($data->taxNumber);

        return $this->priceCalculator->calculateTotalPrice($codeCountry, $data->product, $data->couponCode);
    }
}
