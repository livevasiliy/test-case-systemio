<?php

declare(strict_types=1);

namespace App\Tax;

use App\CalculatePrice\PriceCalculator;

class TaxCalculator
{
    public function __construct(private readonly PriceCalculator $priceCalculator)
    {
    }

    public function calculateTax(float $amount, float $taxAmount): float
    {
        return $this->priceCalculator->getPercent($amount, $taxAmount);
    }
}