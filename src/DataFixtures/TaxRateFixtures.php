<?php

namespace App\DataFixtures;

use App\Entity\TaxRate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TaxRateFixtures extends Fixture
{
    public const GERMANY_TAX_RATE_REFERENCE = 'ge_tax_rate_reference';
    public const ITALY_TAX_RATE_REFERENCE = 'it_tax_rate_reference';
    public const FRANCE_TAX_RATE_REFERENCE = 'fr_tax_rate_reference';
    public const GREECE_TAX_RATE_REFERENCE = 'gr_tax_rate_reference';

    public function load(ObjectManager $manager)
    {
        $germanyRate = new TaxRate(19.0);
        $manager->persist($germanyRate);
        $this->addReference(self::GERMANY_TAX_RATE_REFERENCE, $germanyRate);

        $italyRate = new TaxRate(22.0);
        $manager->persist($italyRate);
        $this->addReference(self::ITALY_TAX_RATE_REFERENCE, $italyRate);

        $franceRate = new TaxRate(20.0);
        $manager->persist($franceRate);
        $this->addReference(self::FRANCE_TAX_RATE_REFERENCE, $franceRate);

        $greeceRate = new TaxRate(24.0);
        $manager->persist($greeceRate);
        $this->addReference(self::GREECE_TAX_RATE_REFERENCE, $greeceRate);

        $manager->flush();
    }
}