<?php

namespace App\Entity;

use App\Repository\TaxNumberRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaxNumberRepository::class)]
class TaxNumber
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 2)]
    private ?string $taxCode = null;

    #[ORM\Column(length: 255)]
    private ?string $mask = null;

    public function __construct(?string $taxCode, ?string $mask)
    {
        $this->taxCode = $taxCode;
        $this->mask = $mask;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxCode(string $taxCode): static
    {
        $this->taxCode = $taxCode;

        return $this;
    }

    public function getMask(): ?string
    {
        return $this->mask;
    }

    public function setMask(string $mask): static
    {
        $this->mask = $mask;

        return $this;
    }
}
