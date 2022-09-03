<?php

namespace MyCompany\Domain\Company\UseCases;

use MyCompany\Domain\Company\Exceptions\CompanyNotFoundException;
use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class GetCompanyUseCase
{
    public function __construct(private PasswordSecurityInterface $passwordSecurity){}

    public function execute(): Company
    {
        $user = $this->passwordSecurity->getCurrentUser();
        if (is_null($user->getCompany())) {
            throw new CompanyNotFoundException("Aucune compagnie n'a été trouvée.");
        }

        return $user->getCompany();
    }
}
