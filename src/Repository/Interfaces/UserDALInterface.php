<?php

namespace MyCompany\Repository\Interfaces;

use MyCompany\Entity\UserAccount;

interface UserDALInterface
{
    public function getByEmail(?string $email): ?UserAccount;
}
