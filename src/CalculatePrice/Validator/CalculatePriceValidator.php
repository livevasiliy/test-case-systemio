<?php

namespace App\CalculatePrice\Validator;

use App\DTO\CalculatePriceDTO;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validation;

class CalculatePriceValidator
{
    public function validate(CalculatePriceDTO $data): void
    {
        $validator = Validation::createValidator();
        $constraints = CalculatePriceDTO::getConstraints();

        foreach ($constraints as $property => $validationConstraints) {
            $propertyValue = $data->{$property};
            $violations = $validator->validate($propertyValue, $validationConstraints);

            if ($violations->count() > 0) {
                throw new ValidationFailedException($propertyValue, $violations);
            }
        }
    }
}