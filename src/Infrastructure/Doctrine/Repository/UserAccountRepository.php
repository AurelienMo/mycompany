<?php

namespace MyCompany\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Domain\User\Ports\Database\UserDALInterface;

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
