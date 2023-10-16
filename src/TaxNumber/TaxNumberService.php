<?php

declare(strict_types=1);

namespace App\TaxNumber;

class TaxNumberService
{
    public function extractCodeCountry(string $taxNumber): string
    {
        return substr($taxNumber, 0, 2);
    }

    private function transformTaxNumberToRegExp(string $taxNumberPattern): string
    {
        $escapedFormat = preg_quote($taxNumberPattern, '/');

        $regexPattern = str_replace(['X', 'Y'], ['\d', '[A-Z]'], $escapedFormat);

        return sprintf('/^%s$/', $regexPattern);
    }
}