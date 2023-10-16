<?php

namespace App\CalculatePrice\Validator;

use App\DTO\CalculatePriceDTO;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CalculatePriceValidator
{
    private const FIELDS = [
        'strProductName' => 'product name',
        'strProductCode' => 'product code',
        'strProductDesc' => 'product description',
    ];

    public function __construct(private readonly ValidatorInterface $validator)
    {
    }

    public function process(CalculatePriceDTO $data): array
    {
        $invalid = [];

        $errors = $this->validator->validate($data->toArray(), $this->getRules());

        foreach ($errors as $error) {
            $invalid[] = [
                'propertyPath' => $error->getPropertyPath(),
                'message' => $error->getMessage(),
            ];
        }

        return [
            'invalid' => $invalid,
        ];
    }

    private function getRules(): Assert\Collection
    {
        $constrains = $this->getConstraints();

        return new Assert\Collection(['fields' => $constrains]);
    }

    private function getConstraints(): array
    {
        $reflector = new ReflectionClass(CalculatePriceDTO::class);

        return array_reduce(
            $reflector->getProperties(),
            static function (array $constrains, ReflectionProperty $property): array {
                foreach ($property->getAttributes() as $attribute) {
                    if (is_subclass_of($attribute->getName(), Constraint::class) && array_key_exists(
                            $property->getName(),
                            self::FIELDS
                        )) {
                        $constrains[self::FIELDS[$property->getName()]][] = $attribute->newInstance();
                    }
                }

                return $constrains;
            },
            []
        );
    }
}