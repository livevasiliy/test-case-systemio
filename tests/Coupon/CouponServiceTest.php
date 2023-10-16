<?php

declare(strict_types=1);

namespace App\Tests\Coupon;

use App\Coupon\CouponService;
use App\Entity\Coupon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CouponServiceTest extends TestCase
{
    private CouponService $couponService;
    private EntityManagerInterface|MockObject $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->couponService = new CouponService($this->entityManager);
    }

    public function testGetCouponByCode(): void
    {
        $couponCode = 'TESTCODE';
        $coupon = new Coupon($couponCode, null, 1.0);

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['code' => $couponCode])
            ->willReturn($coupon);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Coupon::class)
            ->willReturn($repository);

        $result = $this->couponService->getCouponByCode($couponCode);

        $this->assertSame($coupon, $result);
    }

    public function testGetCouponByCodeNotFound(): void
    {
        $couponCode = 'INVALIDCODE';

        $repository = $this->createMock(ObjectRepository::class);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['code' => $couponCode])
            ->willReturn(null);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->with(Coupon::class)
            ->willReturn($repository);

        $this->expectException(InvalidArgumentException::class);

        $this->couponService->getCouponByCode($couponCode);
    }
}
