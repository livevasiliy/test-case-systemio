<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new Product('iPhone', 100.0);
        $manager->persist($product);
        $manager->flush();

        $product = new Product('Headphones', 20.0);
        $manager->persist($product);
        $manager->flush();

        $product = new Product('Phone case', 10.0);
        $manager->persist($product);
        $manager->flush();
    }
}