<?php

declare(strict_types=1);

namespace App\CalculatePrice;

use App\Country\CountryService;
use App\Coupon\CouponService;
use App\Discount\DiscountCalculator;
use App\DTO\CalculatePriceDTO;
use App\Product\ProductService;
use App\Tax\TaxCalculator;
use InvalidArgumentException;

class CalculatePriceWorkflow
{
    private const MAX_LENGTH_COUNTY_CODE_VALUE = 2;
    private const START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE = 0;

    public function __construct(
        private readonly ProductService $productService,
        private readonly CountryService $countryService,
        private readonly CouponService $couponService,
        private readonly DiscountCalculator $discountCalculator,
        private readonly TaxCalculator $taxCalculator,
        private readonly PriceCalculator $priceCalculator
    )
    {
    }

    public function calculatePrice(CalculatePriceDTO $data): float
    {
        $codeCountry = $this->extractCountryCode($data->taxNumber);

        $product = $this->productService->getProductById($data->product);

        $country = $this->countryService->getCountryByCode($codeCountry);

        $coupon = $this->couponService->getCouponByCode($data->couponCode);

        $taxRate = $this->countryService->getCountryTaxRate($country);

        $discount = $this->couponService->getDiscountValue($coupon, $product->getPrice());

        $amountAndDiscount = $this->discountCalculator->calculateAmountWithDiscount($product->getPrice(), $discount);
        $tax = $this->taxCalculator->calculateTax($amountAndDiscount, $taxRate);

        return $this->priceCalculator->calculateTotalPrice($amountAndDiscount, $tax);
    }

    private function extractCountryCode(string $taxNumber): string
    {
        // Проверка на длину строки
        if (strlen($taxNumber) < self::START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE + self::MAX_LENGTH_COUNTY_CODE_VALUE) {
            throw new InvalidArgumentException('Invalid taxNumber length for extracting country code');
        }

        // Проверка на неположительные значения
        if (self::START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE < 0 || self::MAX_LENGTH_COUNTY_CODE_VALUE <= 0) {
            throw new InvalidArgumentException('Invalid start position or length for extracting country code');
        }

        return substr(
            $taxNumber,
            self::START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE,
            self::MAX_LENGTH_COUNTY_CODE_VALUE
        );
    }

}
