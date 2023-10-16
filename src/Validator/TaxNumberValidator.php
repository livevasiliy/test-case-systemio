<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class TaxNumberValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        $regExp = $this->transformTaxNumberToRegExp($value);

        if (!preg_match($regExp, $value)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }

    private function transformTaxNumberToRegExp(string $taxNumber): string
    {
        $escapedFormat = preg_quote($taxNumber, '/');

        $regexPattern = str_replace(['X', 'Y'], ['\d', '[A-Z]'], $escapedFormat);

        return sprintf('/^%s$/', $regexPattern);
    }
}
