<?php

namespace MyCompany\Domain\Client\UseCases;

use MyCompany\Domain\Client\Ports\UseCases\CreateClientDTOInterface;
use MyCompany\Domain\Company\Exceptions\CompanyNotFoundException;
use MyCompany\Domain\Company\Ports\Database\CompanyDALInterface;
use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Ports\DatabaseInterface;
use MyCompany\Domain\Core\Services\ValidatorService;
use MyCompany\Domain\Entity\Client;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class CreateClientUseCase
{
    public function __construct(
        private PasswordSecurityInterface $passwordSecurity,
        private CompanyDALInterface $companyDal,
        private ValidatorService $validatorService,
        private DatabaseInterface $database
    ) {}

    public function execute(CreateClientDTOInterface $dto)
    {
        $user = $this->passwordSecurity->getCurrentUser();
        if (!$user->getCompany() || ($user->getCompany()->getId()->toString() !== $dto->getCompanyId())) {
            throw new AccessDeniedException("Vous ne pouvez pas créer un client pour une autre compagnie.");
        }

        $this->validatorService->validate($dto);

        $company = $this->companyDal->getCompanyById($dto->getCompanyId());

        $client = new Client(
            $company,
            $dto->getFirstname(),
            $dto->getLastname(),
            $dto->getEmail(),
            $dto->getStreetNumber(),
            $dto->getStreetName(),
            $dto->getZipCode(),
            $dto->getCity()
        );

        $this->database->save($client);

        return $client->getId()->toString();
    }
}
