<?php

declare(strict_types=1);

namespace App\Tests\Discount;

use App\Discount\DiscountCalculator;
use PHPUnit\Framework\TestCase;

class DiscountCalculatorTest extends TestCase
{
    public function testCalculateAmountWithDiscount(): void
    {
        $amount = 100.0;
        $discount = 20.0;

        $discountCalculator = new DiscountCalculator();
        $result = $discountCalculator->calculateAmountWithDiscount($amount, $discount);

        $expectedResult = $amount - $discount;
        $this->assertEquals($expectedResult, $result);
    }
}
