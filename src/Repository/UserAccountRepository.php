<?php

namespace MyCompany\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MyCompany\Entity\UserAccount;
use MyCompany\Repository\Interfaces\UserDALInterface;

class UserAccountRepository extends ServiceEntityRepository implements UserDALInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccount::class);
    }

    public function getByEmail(?string $email): ?UserAccount
    {
        return $this->createQueryBuilder('ua')
            ->where('ua.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
