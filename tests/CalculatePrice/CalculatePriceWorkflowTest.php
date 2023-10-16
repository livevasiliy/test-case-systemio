<?php

declare(strict_types=1);

namespace App\Tests\CalculatePrice;

use App\CalculatePrice\CalculatePriceWorkflow;
use App\DTO\CalculatePriceDTO;
use App\CalculatePrice\PriceCalculator;
use App\TaxNumber\TaxNumberService;
use Generator;
use PHPUnit\Framework\TestCase;

class CalculatePriceWorkflowTest extends TestCase
{
    private CalculatePriceWorkflow $calculatePriceWorkflow;
    private PriceCalculator $priceCalculator;
    private TaxNumberService $taxNumberService;

    protected function setUp(): void
    {
        $this->priceCalculator = $this->createMock(PriceCalculator::class);
        $this->taxNumberService = $this->createMock(TaxNumberService::class);
        $this->calculatePriceWorkflow = new CalculatePriceWorkflow(
            $this->priceCalculator,
            $this->taxNumberService
        );
    }

    public function calculatePriceDataProvider(): Generator
    {
        yield 'Valid coupon code' => ['US', 100, 'COUPON123', 90.0];  // Valid coupon
        yield 'Valid no pass coupon code' => ['US', 100, null, 100.0];        // No coupon
        yield 'Invalid pass invalid country code and not pass coupon code' => ['INVALID_COUNTRY', 100, null, 100.0];
    }

    /**
     * @dataProvider calculatePriceDataProvider
     */
    public function testCalculatePrice(
        string  $countryCode,
        int   $productPrice,
        ?string $couponCode,
        float   $expectedTotalPrice
    ): void
    {
        $data = new CalculatePriceDTO($productPrice, '123456', $couponCode);

        $this->taxNumberService
            ->method('handle')
            ->willReturn($countryCode);

        $this->priceCalculator
            ->method('calculateTotalPrice')
            ->willReturn($expectedTotalPrice);

        $result = $this->calculatePriceWorkflow->calculatePrice($data);

        $this->assertEquals($expectedTotalPrice, $result);
    }
}
