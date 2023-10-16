<?php

declare(strict_types=1);

namespace App\Tests\TaxNumber;

use App\Entity\TaxNumber;
use App\TaxNumber\TaxNumberService;
use App\TaxNumber\Validator\TaxNumberValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class TaxNumberServiceTest extends TestCase
{
    public function testHandleValidTaxNumber(): void
    {
        $validTaxNumber = 'DE123456789';
        $codeCountry = 'DE';

        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $taxNumberValidatorMock = $this->createMock(TaxNumberValidator::class);

        $taxNumberService = new TaxNumberService($entityManagerMock, $taxNumberValidatorMock);

        // Configure mocks
        $entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->willReturn($this->createRepositoryMock($codeCountry));

        $taxNumberValidatorMock->expects($this->once())
            ->method('process')
            ->with(
                $this->equalTo($validTaxNumber),
                $this->equalTo('/^DE\d\d\d\d\d\d\d\d\d$/')
            );

        $result = $taxNumberService->handle($validTaxNumber);

        $this->assertSame($codeCountry, $result);
    }

    private function createRepositoryMock(string $codeCountry): object
    {
        $repositoryMock = $this->createMock(ObjectRepository::class);
        $taxNumber = new TaxNumber('DE', 'XXXXXXXXX');
        $repositoryMock->expects($this->once())
            ->method('findOneBy')
            ->with(['taxCode' => $codeCountry])
            ->willReturn($taxNumber);

        return $repositoryMock;
    }
}
