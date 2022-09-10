<?php

namespace MyCompany\Domain\Product\UseCases;

use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Ports\DatabaseInterface;
use MyCompany\Domain\Core\Services\ValidatorService;
use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Product\Ports\UseCases\CreateProductDTOInterface;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class CreateProductUseCase
{
    public function __construct(
        private PasswordSecurityInterface $passwordSecurity,
        private ValidatorService $validator,
        private DatabaseInterface $database
    ) {}

    public function execute(CreateProductDTOInterface $dto): array
    {
        $user = $this->passwordSecurity->getCurrentUser();
        if (!$user->getCompany() instanceof Company) {
            throw new AccessDeniedException("Vous devez être associé à une compagnie pour créer un produit.");
        }

        $this->validator->validate($dto);

        $product = Product::create(
            $dto->getRef(),
            $dto->getDescription(),
            $dto->getUnitPrice(),
            $user->getCompany()
        );
        $this->database->save($product);

        return [
            'id' => $product->getId()->toString(),
        ];
    }
}
