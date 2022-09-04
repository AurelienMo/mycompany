<?php

namespace MyCompany\Domain\Product\UseCases;

use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Ports\DatabaseInterface;
use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Product\Exceptions\ProductNotFoundException;
use MyCompany\Domain\Product\Ports\Database\ProductDALInterface;
use MyCompany\Domain\Product\Ports\UseCases\GetProductDTOInterface;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class DeleteProductUseCase
{
    public function __construct(
        private PasswordSecurityInterface $security,
        private ProductDALInterface $productDAL,
        private DatabaseInterface $database
    ) {}

    public function execute(GetProductDTOInterface $dto): void
    {
        $user = $this->security->getCurrentUser();
        if (!$user->getCompany() instanceof Company) {
            throw new AccessDeniedException("Vous devez être associé à une compagnie pour supprimer un produit.");
        }

        $product = $this->productDAL->getProduct($dto->getProductId());
        if (!$product instanceof Product) {
            throw new ProductNotFoundException("Ce produit n'existe pas.");
        }
        if ($dto->isNeedAuth() && $product->getCompany()->getId()->toString() !== $user->getCompany()->getId()->toString()) {
            throw new AccessDeniedException("Vous ne pouvez pas supprimer un produit qui ne vous appartient pas.");
        }

        $this->database->delete($product);
        $this->database->save();
    }
}
