<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CalculatePriceDTO extends AbstractDTO
{
    public int $product;

    public string $taxNumber;

    public ?string $couponCode;

    public function __construct(int $product, string $taxNumber, ?string $couponCode)
    {
        $this->product = $product;
        $this->taxNumber = $taxNumber;
        $this->couponCode = $couponCode;
    }

    public static function getConstraints(): array
    {
        return [
            'product' => [
                new Assert\NotBlank(),
                new Assert\Positive(),
            ],
            'taxNumber' => [
                new Assert\NotBlank(),
            ],
        ];
    }
}