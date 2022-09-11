<?php

namespace MyCompany\Domain\Client\UseCases;

use Doctrine\ORM\Tools\Pagination\Paginator;
use MyCompany\Domain\Client\Ports\Database\ClientDALInterface;
use MyCompany\Domain\Client\Ports\UseCases\ListClientsDTOInterface;
use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class ListClientsUseCase
{
    public function __construct(
        private ClientDALInterface $clientDal,
        private PasswordSecurityInterface $passwordSecurity
    ) {}

    public function execute(ListClientsDTOInterface $dto): Paginator
    {
        $user = $this->passwordSecurity->getCurrentUser();
        if (!$user->getCompany() || $dto->getCompanyId() !== $user->getCompany()->getId()->toString()) {
            throw new AccessDeniedException("Vous ne pouvez pas lister les cliens d'une autre compagnie.");
        }
        return $this->clientDal->getClientsByCompany(
            $dto->getCompanyId(),
            $dto->getPage(),
            $dto->getLimit(),
            $dto->getQueryParams()
        );
    }
}
