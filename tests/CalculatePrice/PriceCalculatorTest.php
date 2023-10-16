<?php

declare(strict_types=1);

namespace App\Tests\CalculatePrice;

use App\CalculatePrice\PriceCalculator;
use App\Coupon\CouponService;
use App\Country\CountryService;
use App\Discount\DiscountCalculator;
use App\Entity\Country;
use App\Entity\Coupon;
use App\Entity\CouponType;
use App\Entity\Product;
use App\Entity\TaxRate;
use App\Product\ProductService;
use Generator;
use PHPUnit\Framework\TestCase;

class PriceCalculatorTest extends TestCase
{
    private PriceCalculator $priceCalculator;
    private ProductService $productService;
    private CountryService $countryService;
    private CouponService $couponService;
    private DiscountCalculator $discountCalculator;

    protected function setUp(): void
    {
        $this->productService = $this->createMock(ProductService::class);
        $this->countryService = $this->createMock(CountryService::class);
        $this->couponService = $this->createMock(CouponService::class);
        $this->discountCalculator = $this->createMock(DiscountCalculator::class);
        $this->priceCalculator = new PriceCalculator(
            $this->productService,
            $this->countryService,
            $this->couponService,
            $this->discountCalculator
        );
    }

    public function calculateTotalPriceDataProvider(): Generator
    {
        yield 'Valid coupon with 20% discount' => ['DE', 100, 'D15', CouponType::PERCENT_TYPE_VALUE, 15.0, 19.0, 101.15];
        yield 'Valid No coupon with 10 tax' => ['DE', 100, null, null, null, 10.0, 110.0];
        yield 'Valid coupon with fixed 50 tax' => ['DE', 100, 'FIXED50', CouponType::FIXED_TYPE_VALUE, 50.0, 19.0, 59.5];
        yield 'Invalid country with 15% tax' => ['INVALID_COUNTRY', 100, 'D15', CouponType::PERCENT_TYPE_VALUE, 15.0, 15.0, 97.75];
    }

    /**
     * @dataProvider calculateTotalPriceDataProvider
     */
    public function testCalculateTotalPrice(
        string  $countryCode,
        float   $productPrice,
        ?string $couponCode,
        ?string $couponType,
        ?float  $couponValue,
        float   $taxRate,
        float   $expectedTotalPrice
    ): void
    {
        $productId = 123;

        $product = new Product('foo', $productPrice);
        $product->setId($productId);

        $this->productService
            ->method('getProductById')
            ->willReturn($product);

        $this->countryService
            ->method('getCountryByCode')
            ->willReturn(new Country($countryCode, 'USA', new TaxRate($taxRate)));

        $coupon = ($couponCode === null) ? null : new Coupon(
            $couponCode,
            new CouponType($couponType),
            $couponValue
        );

        $this->couponService
            ->method('getCouponByCode')
            ->willReturn($coupon);

        $this->countryService
            ->method('getCountryTaxRate')
            ->willReturn($taxRate);

        $this->discountCalculator
            ->method('calculateAmountWithDiscount')
            ->willReturnCallback(fn($price, $discount) => $price - ($price * $discount / 100));

        $result = $this->priceCalculator->calculateTotalPrice($countryCode, $productId, $couponCode);

        $this->assertEquals($expectedTotalPrice, $result);
    }
}
