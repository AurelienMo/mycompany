<?php

namespace MyCompany\UI\Adapters\Http\Client;

use MyCompany\Domain\Client\Ports\UseCases\ListClientsDTOInterface;
use MyCompany\Domain\Core\Services\Pagination\PaginationValues;

class ListClients implements ListClientsDTOInterface
{
    public function __construct(
        private string $companyId,
        private PaginationValues $paginationValues,
        private array $queryParams
    ) {}

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function getPage(): int
    {
        return $this->paginationValues->getPage();
    }

    public function getLimit(): int
    {
        return $this->paginationValues->getLimit();
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }
}
