<?php

namespace MyCompany\UI\Adapters\Http\Product;

use MyCompany\Domain\Product\Ports\UseCases\CreateProductDTOInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateProductHttp implements CreateProductDTOInterface
{
    private string|null $ref;

    private string|null $description;

    private float|null $unitPrice;

    public function __construct(array $payload)
    {
        $this->ref = $payload['ref'] ?? null;
        $this->description = $payload['description'] ?? null;
        $this->unitPrice = $payload['unitPrice'] ?? null;
    }

    public function getRef(): ?string
    {
        return $this->ref;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getUnitPrice(): ?float
    {
        return $this->unitPrice;
    }
}
