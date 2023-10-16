<?php

declare(strict_types=1);

namespace App\Country;

use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class CountryService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getCountryByCode(string $codeCountry): mixed
    {
        $country = $this->entityManager->getRepository(Country::class)->findOneBy(['code' => $codeCountry]);

        if ($country === null) {
            throw new InvalidArgumentException('Country not found');
        }

        return $country;
    }

    public function getCountryTaxRate(Country $country): ?float
    {
        return $country->getTaxRate()->getRate();
    }
}