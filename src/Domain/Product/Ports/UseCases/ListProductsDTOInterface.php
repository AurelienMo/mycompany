<?php

namespace MyCompany\Domain\Product\Ports\UseCases;

interface ListProductsDTOInterface
{
    public function getPage(): int;

    public function getLimit(): int;

    public function getQueryParams(): array;
}
