<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Coupon;
use App\Entity\CouponType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CouponFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Get references to coupon types
        /** @var CouponType $fixedCouponType */
        $fixedCouponType = $this->getReference('fixed_coupon_type');

        /** @var CouponType $percentCouponType */
        $percentCouponType = $this->getReference('percent_coupon_type');

        // Create coupons
        $fixedCoupon = new Coupon();
        $fixedCoupon->setCode('FIXED50')
            ->setCouponType($fixedCouponType)
            ->setDiscountValue(50);  // Assume this is a fixed amount discount
        $manager->persist($fixedCoupon);

        $percentCoupon = new Coupon();
        $percentCoupon->setCode('PERCENT30')
            ->setCouponType($percentCouponType)
            ->setDiscountValue(30);  // Assume this is a percentage discount
        $manager->persist($percentCoupon);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CouponTypesFixtures::class
        ];
    }

}