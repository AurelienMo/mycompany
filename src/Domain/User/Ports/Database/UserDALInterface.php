<?php

namespace MyCompany\Domain\User\Ports\Database;

use MyCompany\Domain\Entity\UserAccount;

interface UserDALInterface
{
    public function getByEmail(?string $email): ?UserAccount;
}
