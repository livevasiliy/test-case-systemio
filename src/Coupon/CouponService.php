<?php

declare(strict_types=1);

namespace App\Coupon;

use App\CalculatePrice\PriceCalculator;
use App\Entity\Coupon;
use App\Entity\CouponType;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class CouponService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly PriceCalculator $priceCalculator
    )
    {
    }

    public function getCouponByCode(string $couponCode): mixed
    {
        $coupon = $this->entityManager->getRepository(Coupon::class)->findOneBy(['code' => $couponCode]);

        if ($coupon === null) {
            throw new InvalidArgumentException('Coupon not found');
        }
        return $coupon;
    }

    public function getDiscountValue(Coupon $coupon, float $amount): float
    {
        $discount = 0.0;
        if ($coupon->getCouponType()->getName() === CouponType::PERCENT_TYPE_VALUE) {
            $discount = $this->priceCalculator->getPercent($amount, $coupon->getDiscountValue());
        } elseif ($coupon->getCouponType()->getName() === CouponType::FIXED_TYPE_VALUE) {
            $discount = $coupon->getDiscountValue();
        }
        return (float) $discount;
    }
}
