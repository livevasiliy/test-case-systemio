<?php

declare(strict_types=1);

namespace App\Tests\Product;

use App\Entity\Product;
use App\Product\ProductService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProductServiceTest extends TestCase
{
    private ProductService $productService;
    private EntityManagerInterface|MockObject $entityManager;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->productService = new ProductService($this->entityManager);
    }

    public function productDataProvider(): Generator
    {
        yield 'Valid product by id exists' => [13, true];
        yield 'Invalid product by id does not exist' => [999, false];
    }

    /**
     * @dataProvider productDataProvider
     */
    public function testGetProductById(int $productId, bool $productExists): void
    {
        // Prepare a dummy product
        $product = ($productExists) ? new Product('foo', 20.0) : null;

        // Mock the repository to return the dummy product
        $repository = $this->createMock(ObjectRepository::class);
        $repository
            ->method('find')
            ->willReturn($product);

        // Mock getRepository to return the repository
        $this->entityManager
            ->method('getRepository')
            ->willReturn($repository);

        if ($productExists) {
            // Call the method we are testing
            $result = $this->productService->getProductById($productId);

            // Assert that the result is the same as the dummy product
            $this->assertSame($product, $result);
        } else {
            // Call the method we are testing and expect an exception
            $this->expectException(InvalidArgumentException::class);
            $this->productService->getProductById($productId);
        }
    }
}
