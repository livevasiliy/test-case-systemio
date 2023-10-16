<?php

declare(strict_types=1);

namespace App\Discount;

class DiscountCalculator
{
    public function calculateAmountWithDiscount(float $amount, float $discount): float
    {
        return $amount - $discount;
    }
}