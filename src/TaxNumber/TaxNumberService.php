<?php

declare(strict_types=1);

namespace App\TaxNumber;

use App\Entity\TaxNumber;
use App\TaxNumber\Validator\TaxNumberValidator;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;

class TaxNumberService
{
    private const MAX_LENGTH_COUNTY_CODE_VALUE = 2;
    private const START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE = 0;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TaxNumberValidator $taxNumberValidator
    )
    {
    }


    public function handle(string $taxNumber): string
    {
        $codeCountry = $this->extractCountryCodeFromTaxNumber($taxNumber);

        $mask = $this->getMask($codeCountry);
        $regExp = $this->transformTaxNumberToRegExp($codeCountry, $mask);

        $this->taxNumberValidator->process($taxNumber, $regExp);

        return $codeCountry;
    }

    private function extractCountryCodeFromTaxNumber(string $taxNumber): string
    {
        // Проверка на длину строки
        if (strlen($taxNumber) < self::START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE + self::MAX_LENGTH_COUNTY_CODE_VALUE) {
            throw new InvalidArgumentException('Invalid taxNumber length for extracting country code');
        }

        // Проверка на неположительные значения
        if (self::START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE < 0 || self::MAX_LENGTH_COUNTY_CODE_VALUE <= 0) {
            throw new InvalidArgumentException('Invalid start position or length for extracting country code');
        }

        return substr(
            $taxNumber,
            self::START_POSITION_COUNTRY_CODE_IN_TAX_NUMBER_VALUE,
            self::MAX_LENGTH_COUNTY_CODE_VALUE
        );
    }

    private function transformTaxNumberToRegExp($countryCode, string $taxNumberPattern): string
    {
        $escapedFormat = preg_quote($taxNumberPattern, '/');

        $regexPattern = str_replace(['X', 'Y'], ['\d', '[A-Z]'], $escapedFormat);

        return sprintf('/^%s%s$/',$countryCode, $regexPattern);
    }

    private function getMask(string $codeCountry): ?string
    {
        $taxNumber = $this->entityManager->getRepository(TaxNumber::class)->findOneBy(['taxCode' => $codeCountry]);

        if ($taxNumber === null) {
            throw new InvalidArgumentException(sprintf("Not found tax number for code country %s.", $codeCountry));
        }

        return trim($taxNumber->getMask());
    }
}