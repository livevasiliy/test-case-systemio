<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\CouponType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CouponTypesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $fixedCouponType = new CouponType(CouponType::FIXED_TYPE_VALUE);
        $manager->persist($fixedCouponType);

        $percentCouponType = new CouponType(CouponType::PERCENT_TYPE_VALUE);
        $manager->persist($percentCouponType);

        $manager->flush();

        // Store references to coupon types for later use
        $this->addReference('fixed_coupon_type', $fixedCouponType);
        $this->addReference('percent_coupon_type', $percentCouponType);
    }

}