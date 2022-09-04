<?php

namespace MyCompany\UI\Adapters\Http\Product;

use MyCompany\Domain\Product\Ports\UseCases\GetProductDTOInterface;

class GetProductHttp implements GetProductDTOInterface
{
    private string $productId;

    public function __construct(string $id)
    {
        $this->productId = $id;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function isNeedAuth(): bool
    {
        return true;
    }
}
