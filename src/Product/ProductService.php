<?php

declare(strict_types=1);

namespace App\Product;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class ProductService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getProductById(int $productId): mixed
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        if ($product === null) {
            throw new InvalidArgumentException('Product not found');
        }

        return $product;
    }
}