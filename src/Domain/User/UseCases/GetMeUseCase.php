<?php

namespace MyCompany\Domain\User\UseCases;

use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;

class GetMeUseCase
{
    public function __construct(
        private PasswordSecurityInterface $passwordSecurity
    ) {
    }

    public function execute(): UserAccount
    {
        return $this->passwordSecurity->getCurrentUser();
    }
}
