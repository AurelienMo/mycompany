<?php

namespace MyCompany\UI\Adapters\Http\Product;

use MyCompany\Domain\Product\Ports\UseCases\UpdateProductDTOInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

class UpdateProduct implements UpdateProductDTOInterface
{
    private string $productId;

    private ?string $ref;

    private ?string $description;

    private ?float $unitPrice;

    public function __construct(string $productId, array $payload)
    {
        $this->productId = $productId;
        $this->ref = $payload['ref'] ?? null;
        $this->description = $payload['description'] ?? null;
        $this->unitPrice = $payload['unitPrice'] ?? null;
    }

    public function getProductId(): string
    {
        return $this->productId;
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
