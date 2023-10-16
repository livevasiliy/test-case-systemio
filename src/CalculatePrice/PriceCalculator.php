<?php

declare(strict_types=1);

namespace App\CalculatePrice;

use App\Country\CountryService;
use App\Coupon\CouponService;
use App\Discount\DiscountCalculator;
use App\Entity\Coupon;
use App\Entity\CouponType;
use App\Product\ProductService;

class PriceCalculator
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CountryService $countryService,
        private readonly CouponService $couponService,
        private readonly DiscountCalculator $discountCalculator,
    )
    {
    }

    public function calculateTotalPrice(string $codeCountry, int $productId, ?string $couponCode): float
    {
        $product = $this->productService->getProductById($productId);

        $country = $this->countryService->getCountryByCode($codeCountry);

        $coupon = $this->couponService->getCouponByCode($couponCode);

        $taxRate = $this->countryService->getCountryTaxRate($country);

        $discount = $this->getDiscountValue($coupon, $product->getPrice());

        $amountAndDiscount = $this->discountCalculator->calculateAmountWithDiscount($product->getPrice(), $discount);

        $tax = $this->calculateTax($amountAndDiscount, $taxRate);

        return $amountAndDiscount + $tax;
    }

    public function getPercent(float $amount, float $percent): float
    {
        return ($amount * $percent) / 100;
    }

    public function getDiscountValue(?Coupon $coupon, float $amount): float
    {
        $discount = 0.0;
        if ($coupon) {
            if ($coupon->getCouponType()->getName() === CouponType::PERCENT_TYPE_VALUE) {
                $discount = $this->getPercent($amount, $coupon->getDiscountValue());
            } elseif ($coupon->getCouponType()->getName() === CouponType::FIXED_TYPE_VALUE) {
                $discount = $coupon->getDiscountValue();
            }
        }
        return (float) $discount;
    }

    public function calculateTax(float $amount, float $taxAmount): float
    {
        return $this->getPercent($amount, $taxAmount);
    }
}