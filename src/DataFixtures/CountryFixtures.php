<?php

namespace App\DataFixtures;

use App\Entity\Country;
use App\Entity\TaxRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CountryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var TaxRate $germanyTaxRate */
        $germanyTaxRate = $this->getReference(TaxRateFixtures::GERMANY_TAX_RATE_REFERENCE);
        $germany = new Country('DE', 'Germany', $germanyTaxRate);
        $manager->persist($germany);

        /** @var TaxRate $italyTaxRate */
        $italyTaxRate = $this->getReference(TaxRateFixtures::ITALY_TAX_RATE_REFERENCE);
        $italy = new Country('IT', 'Italy', $italyTaxRate);
        $manager->persist($italy);

        /** @var TaxRate $franceTaxRate */
        $franceTaxRate = $this->getReference(TaxRateFixtures::FRANCE_TAX_RATE_REFERENCE);
        $france = new Country('FR', 'France', $franceTaxRate);
        $manager->persist($france);

        /** @var TaxRate $greeceTaxRate */
        $greeceTaxRate = $this->getReference(TaxRateFixtures::GREECE_TAX_RATE_REFERENCE);
        $greece = new Country('GE', 'Greece', $greeceTaxRate);
        $manager->persist($greece);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TaxRateFixtures::class
        ];
    }
}