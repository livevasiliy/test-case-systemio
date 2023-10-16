<?php

declare(strict_types=1);

namespace App\Tests\Country;

use App\Country\CountryService;
use App\Entity\Country;
use App\Entity\TaxRate;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CountryServiceTest extends TestCase
{
    private EntityManagerInterface|MockObject $entityManager;
    private CountryService $countryService;

    protected function setUp(): void
    {
        // Создаем заглушку для EntityManager
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->countryService = new CountryService($this->entityManager);
    }

    public function countryDataProvider(): Generator
    {
        yield 'Successfully found country data' => ['US', 'USA', 20.0, true];
        yield 'Failure to find country data' => ['INVALIDCODE', null, null, false];
    }

    /**
     * @dataProvider countryDataProvider
     */
    public function testGetCountryByCode(
        string $countryCode,
        ?string $expectedName,
        ?float $expectedTaxRate,
        bool $countryExists
    ): void {
        // Подготовка заглушки для Country
        $country = null;
        if ($countryExists) {
            $country = new Country($countryCode, $expectedName, new TaxRate($expectedTaxRate));
        }

        // Mock the repository to return the dummy country
        $repository = $this->createMock(ObjectRepository::class);
        $repository
            ->method('findOneBy')
            ->willReturn($country);

        // Mock getRepository to return the repository
        $this->entityManager
            ->method('getRepository')
            ->willReturn($repository);

        if ($countryExists) {
            // Call the method we are testing
            $result = $this->countryService->getCountryByCode($countryCode);

            // Assert that the result is the same as the dummy country
            $this->assertSame($country, $result);
        } else {
            $this->expectException(InvalidArgumentException::class);
            $this->countryService->getCountryByCode($countryCode);
        }
    }
}
