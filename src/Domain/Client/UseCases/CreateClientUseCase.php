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
use MyCompany\Domain\Services\EncryptCipherService;

class CreateClientUseCase
{
    public function __construct(
        private PasswordSecurityInterface $passwordSecurity,
        private CompanyDALInterface $companyDal,
        private ValidatorService $validatorService,
        private DatabaseInterface $database,
        private EncryptCipherService $encryptCipher
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
            $this->encryptCipher->encrypt($dto->getFirstname()),
            $this->encryptCipher->encrypt($dto->getLastname()),
            $this->encryptCipher->encrypt($dto->getEmail()),
            $dto->getStreetNumber() ? $this->encryptCipher->encrypt($dto->getStreetNumber()) : null,
            $this->encryptCipher->encrypt($dto->getStreetName()),
            $this->encryptCipher->encrypt($dto->getZipCode()),
            $this->encryptCipher->encrypt($dto->getCity())
        );

        $this->database->save($client);

        return $client->getId()->toString();
    }
}
