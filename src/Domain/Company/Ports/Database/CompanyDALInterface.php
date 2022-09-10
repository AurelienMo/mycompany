<?php

namespace MyCompany\Domain\Company\Ports\Database;

use MyCompany\Domain\Entity\Company;

interface CompanyDALInterface
{
    public function getCompanyById(string $companyId): ?Company;
}
