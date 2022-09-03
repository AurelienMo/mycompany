<?php

namespace MyCompany\Domain\Company\UseCases;

use MyCompany\Domain\Company\Ports\UseCases\CreateCompanyDTOInterface;
use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Ports\DatabaseInterface;
use MyCompany\Domain\Core\Services\ValidatorService;
use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class CreateCompanyUseCase
{
    public function __construct(
        private PasswordSecurityInterface $passwordSecurity,
        private ValidatorService $validator,
        private DatabaseInterface $database
    ) {}

    public function execute(CreateCompanyDTOInterface $dto): array
    {
        $this->validator->validate($dto);
        $user = $this->passwordSecurity->getCurrentUser();
        if ($user && $user->getCompany() instanceof Company) {
            throw new AccessDeniedException('Vous êtes déjà associé à une compagnie.');
        }

        $company = $this->createCompany($dto);
        $user->attachCompany($company);
        $this->database->save($company);

        return [
            'id' => $company->getId()->toString(),
        ];
    }

    private function createCompany(CreateCompanyDTOInterface $dto): Company
    {
        return Company::create(
            $dto->getFirstname(),
            $dto->getLastname(),
            $dto->getCompanyName(),
            $dto->isFreelance(),
            $dto->getVatNumber(),
            $dto->getStreetNumber(),
            $dto->getStreetName(),
            $dto->getZipCode(),
            $dto->getCity()
        );
    }
}
