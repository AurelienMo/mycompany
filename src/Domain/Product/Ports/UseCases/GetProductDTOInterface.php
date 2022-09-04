<?php

namespace MyCompany\Domain\Product\Ports\UseCases;

interface GetProductDTOInterface
{
    public function getProductId(): string;

    public function isNeedAuth(): bool;
}
