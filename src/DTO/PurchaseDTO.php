<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PurchaseDTO extends AbstractDTO
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $product;

    #[Assert\NotBlank]
    public string $taxNumber;

    public string $couponCode;

    #[Assert\NotBlank]
    public string $paymentProcessor;

    public function __construct(int $product, string $taxNumber, ?string $couponCode, string $paymentProcessor)
    {
        $this->product = $product;
        $this->taxNumber = $taxNumber;
        $this->couponCode = $couponCode;
        $this->paymentProcessor = $paymentProcessor;
    }
}
