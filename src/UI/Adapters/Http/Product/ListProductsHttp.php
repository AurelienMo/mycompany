<?php

namespace MyCompany\UI\Adapters\Http\Product;

use MyCompany\Domain\Core\Services\Pagination\PaginationValues;
use MyCompany\Domain\Product\Ports\UseCases\ListProductsDTOInterface;

class ListProductsHttp implements ListProductsDTOInterface
{
    private int $page;

    private int $limit;

    private array $queryParams;

    public function __construct(PaginationValues $paginationValues, array $queryParams)
    {
        $this->page = $paginationValues->getPage();
        $this->limit = $paginationValues->getLimit();
        $this->queryParams = $queryParams;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }
}
