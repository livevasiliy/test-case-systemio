<?php

declare(strict_types=1);

namespace App\TaxNumber\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaxNumberValidator
{
    public function __construct(
        private readonly ValidatorInterface $validator
    )
    {
    }

    public function process(string $value, string $regExp): void
    {
        $errors = $this->validator->validate($value, [
            new Assert\NotBlank(),
            new Assert\Regex(pattern: $regExp)
        ]);

        if ($errors->count() > 0) {
            throw new ValidationFailedException($value, $errors);
        }
    }
}