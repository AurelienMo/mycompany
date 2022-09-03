<?php

namespace MyCompany\Domain\Security\Ports;

use MyCompany\Domain\Entity\UserAccount;

interface PasswordSecurityInterface
{
    public function hash(UserAccount $user, string $password): string;

    public function generateToken(UserAccount $user): string;

    public function generateRefreshToken(UserAccount $user): string;
}
