<?php

namespace MyCompany\Domain\Client\Ports\Database;

use Doctrine\ORM\Tools\Pagination\Paginator;

interface ClientDALInterface
{
    public function getClientsByCompany(
        string $companyId,
        int $page = 1,
        int $limit = 15,
        array $queryParams = []
    ): Paginator;
}
