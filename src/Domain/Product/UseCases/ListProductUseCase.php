<?php

namespace MyCompany\Domain\Product\UseCases;

use Doctrine\ORM\Tools\Pagination\Paginator;
use MyCompany\Domain\Product\Ports\Database\ProductDALInterface;
use MyCompany\Domain\Product\Ports\UseCases\ListProductsDTOInterface;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class ListProductUseCase
{
    public function __construct(
        private ProductDALInterface $productDal,
        private PasswordSecurityInterface $passwordSecurity
    ) {}

    public function execute(ListProductsDTOInterface $dto): Paginator
    {
        return $this->productDal->listProductsRelatedAuthUser(
            $this->passwordSecurity->getCurrentUser(),
            $dto->getPage(),
            $dto->getLimit(),
            $dto->getQueryParams()
        );
    }
}
