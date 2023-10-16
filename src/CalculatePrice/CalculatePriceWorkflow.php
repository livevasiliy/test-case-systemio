<?php

declare(strict_types=1);

namespace App\CalculatePrice;

use App\CalculatePrice\Validator\CalculatePriceValidator;
use App\DTO\CalculatePriceDTO;
use App\Entity\Country;
use App\Entity\Coupon;
use App\Entity\CouponType;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class CalculatePriceWorkflow
{
    private const MAX_LENGTH_COUNTY_CODE_VALUE = 2;
    private const START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE = 0;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CalculatePriceValidator $validator
    )
    {
    }

    public function calculatePrice(CalculatePriceDTO $data): float
    {
        $violations = $this->validator->validate($data);



        $product = $this->getProduct($data->product);
        $codeCountry = $this->extractCountryCode($data->taxNumber);
        $country = $this->getCountry($codeCountry);
        $coupon = $this->getCoupon($data->couponCode);

        $taxRate = $country->getTaxRate()->getRate();

        $discount = $this->getDiscountValue($coupon, $product->getPrice());

        return $this->calculateTotalPrice($product->getPrice(), $taxRate, $discount);
    }

    public function getPercent(float $amount, float $percent): float
    {
        return ($amount * $percent) / 100;
    }

    private function getProduct(int $productId): mixed
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if ($product === null) {
            throw new InvalidArgumentException('Product not found');
        }

        return $product;
    }

    private function extractCountryCode(string $taxNumber): string
    {
        return substr($taxNumber, self::START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE, self::MAX_LENGTH_COUNTY_CODE_VALUE);
    }

    private function getCountry(string $codeCountry): mixed
    {
        $country = $this->entityManager->getRepository(Country::class)->findOneBy(['code' => $codeCountry]);

        if ($country === null) {
            throw new InvalidArgumentException('Country not found');
        }
        return $country;
    }

    private function getCoupon(string $couponCode): mixed
    {
        $coupon = $this->entityManager->getRepository(Coupon::class)->findOneBy(['code' => $couponCode]);

        if ($coupon === null) {
            throw new InvalidArgumentException('Coupon not found');
        }
        return $coupon;
    }

    private function calculateTotalPrice(float $productPrice, float $taxPrice, float $discount): float
    {
        $amountAndDiscount = $productPrice - $discount;
        $tax = $this->getPercent($amountAndDiscount, $taxPrice);

        return $amountAndDiscount + $tax;
    }

    private function getDiscountValue(Coupon $coupon, float $amount): float|int
    {
        $discount = 0.0;
        if ($coupon->getCouponType()->getName() === CouponType::PERCENT_TYPE_VALUE) {
            $discount = $this->getPercent($amount, $coupon->getDiscountValue());
        } elseif ($coupon->getCouponType()->getName() === CouponType::FIXED_TYPE_VALUE) {
            $discount = $coupon->getDiscountValue();
        }
        return $discount;
    }
}