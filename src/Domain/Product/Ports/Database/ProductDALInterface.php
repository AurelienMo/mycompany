<?php

namespace MyCompany\Domain\Product\Ports\Database;

use Doctrine\ORM\Tools\Pagination\Paginator;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Entity\UserAccount;

interface ProductDALInterface
{
    public function listProductsRelatedAuthUser(UserAccount $user, int $page = 1, int $limit = 15, array $queryParams = []): Paginator;

    public function getProduct(string $id): ?Product;
}
