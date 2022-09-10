<?php

namespace MyCompany\Domain\Product\UseCases;

use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Ports\DatabaseInterface;
use MyCompany\Domain\Core\Services\ValidatorService;
use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Product\Exceptions\ProductNotFoundException;
use MyCompany\Domain\Product\Ports\Database\ProductDALInterface;
use MyCompany\Domain\Product\Ports\UseCases\UpdateProductDTOInterface;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class UpdateProductUseCase
{
    public function __construct(
        private ProductDALInterface $productDAL,
        private PasswordSecurityInterface $passwordSecurity,
        private ValidatorService $validatorService,
        private DatabaseInterface $database
    ) {}

    public function execute(UpdateProductDTOInterface $dto): void
    {
        $this->validatorService->validate($dto);
        $user = $this->passwordSecurity->getCurrentUser();
        if (!$user->getCompany() instanceof Company) {
            throw new AccessDeniedException("Vous devez être associé à une compagnie pour mettre à jour un produit.");
        }
        $product = $this->productDAL->getProduct($dto->getProductId());
        if (!$product instanceof Product) {
            throw new ProductNotFoundException("Ce produit n'existe pas.");
        }

        if ($product->getCompany() !== $user->getCompany()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à accéder au produit d'une autre compagnie.");
        }

        $product->update(
            $dto->getRef(),
            $dto->getDescription(),
            $dto->getUnitPrice()
        );

        $this->database->save();
    }
}
