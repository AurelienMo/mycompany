<?php

namespace MyCompany\Domain\Product\UseCases;

use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Product\Exceptions\ProductNotFoundException;
use MyCompany\Domain\Product\Ports\Database\ProductDALInterface;
use MyCompany\Domain\Product\Ports\UseCases\GetProductDTOInterface;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class GetProductUseCase
{
    public function __construct(
        private ProductDALInterface $productDal,
        private PasswordSecurityInterface $passwordSecurity
    ) {}

    public function execute(GetProductDTOInterface $dto): Product
    {
        $product = $this->productDal->getProduct($dto->getProductId());
        if (!$product instanceof Product) {
            throw new ProductNotFoundException("Ce produit n'existe pas.");
        }
        $user = $this->passwordSecurity->getCurrentUser();

        if ($dto->isNeedAuth() && $product->getCompany()->getId() !== $user->getCompany()->getId()) {
            throw new AccessDeniedException("Vous n'avez pas accès à ce produit.");
        }

        return $product;
    }
}
