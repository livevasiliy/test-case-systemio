<?php

declare(strict_types=1);

namespace App\Tests\TaxNumber\Validator;

use App\TaxNumber\Validator\TaxNumberValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaxNumberValidatorTest extends TestCase
{
    public function testProcessValidValue(): void
    {
        $validTaxNumber = 'DE123456789';

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->expects($this->once())
            ->method('validate')
            ->with(
                $this->equalTo($validTaxNumber),
                $this->equalTo([
                    new NotBlank(),
                    new Regex(['pattern' => '^DE\d\d\d\d\d\d\d\d\d$/'])
                ])
            );

        $taxNumberValidator = new TaxNumberValidator($validatorMock);
        $taxNumberValidator->process($validTaxNumber, '^DE\d\d\d\d\d\d\d\d\d$/');
    }

    public function testProcessInvalidValue(): void
    {
        $invalidTaxNumber = 'InvalidTaxNumber';

        $violations = new ConstraintViolationList([]);

        $validatorMock = $this->createMock(ValidatorInterface::class);
        $validatorMock->expects($this->once())
            ->method('validate')
            ->willThrowException(new ValidationFailedException($invalidTaxNumber, $violations));

        $taxNumberValidator = new TaxNumberValidator($validatorMock);

        $this->expectException(ValidationFailedException::class);
        $taxNumberValidator->process($invalidTaxNumber, '^DE\d\d\d\d\d\d\d\d\d$/');
    }
}
