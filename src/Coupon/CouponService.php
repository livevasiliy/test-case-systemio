<?php

declare(strict_types=1);

namespace App\Coupon;

use App\Entity\Coupon;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class CouponService
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function getCouponByCode(?string $couponCode): mixed
    {
        if ($couponCode) {
            $coupon = $this->entityManager->getRepository(Coupon::class)->findOneBy(['code' => $couponCode]);

            if ($coupon === null) {
                throw new InvalidArgumentException('Coupon not found');
            }
            return $coupon;
        }

        return null;
    }
}
