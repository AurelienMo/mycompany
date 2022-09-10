<?php

namespace MyCompany\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MyCompany\Domain\Company\Ports\Database\CompanyDALInterface;
use MyCompany\Domain\Entity\Company;

class CompanyRepository extends ServiceEntityRepository implements CompanyDALInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Company::class);
    }

    public function getCompanyById(string $companyId): ?Company
    {
        return $this->createQueryBuilder('c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $companyId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
