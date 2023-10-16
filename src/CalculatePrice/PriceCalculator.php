<?php

declare(strict_types=1);

namespace App\CalculatePrice;

class PriceCalculator
{
    public function calculateTotalPrice(float $amountWithDiscount, float $tax): float
    {
        return $amountWithDiscount + $tax;
    }

    public function getPercent(float $amount, float $percent): float
    {
        return ($amount * $percent) / 100;
    }
}