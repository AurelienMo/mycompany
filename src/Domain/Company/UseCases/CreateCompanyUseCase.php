<?php

namespace MyCompany\Domain\Company\UseCases;

use MyCompany\Domain\Company\Exceptions\CompanyNotFoundException;
use MyCompany\Domain\Company\Ports\UseCases\CreateCompanyDTOInterface;
use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Ports\DatabaseInterface;
use MyCompany\Domain\Core\Services\ValidatorService;
use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class CreateCompanyUseCase
{
    public function __construct(
        private PasswordSecurityInterface $passwordSecurity,
        private ValidatorService $validator,
        private DatabaseInterface $database
    ) {}

    public function execute(CreateCompanyDTOInterface $dto, string $method): array
    {
        $this->validator->validate($dto);
        $user = $this->passwordSecurity->getCurrentUser();
        if ($method === 'POST') {
            $id = $this->createCompany($dto, $user);

            return [
                'id' => $id
            ];
        } else {
            $this->updateCompany($dto, $user);

            return [];
        }
    }

    private function createCompany(CreateCompanyDTOInterface $dto, UserAccount $user): string
    {
        if ($user->getCompany() instanceof Company) {
            throw new AccessDeniedException('Vous êtes déjà associé à une compagnie.');
        }

        $company = Company::create(
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

        $user->attachCompany($company);
        $this->database->save($company);

        return $company->getId()->toString();
    }

    private function updateCompany(CreateCompanyDTOInterface $dto, UserAccount $user): void
    {
        if (!$user->getCompany() instanceof Company) {
            throw new CompanyNotFoundException("Aucune compagnie n'a été trouvée.");
        }

        $user->getCompany()->update($dto);
        $this->database->save();
    }
}
