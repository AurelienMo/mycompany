<?php

namespace MyCompany\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use MyCompany\Domain\Client\Ports\Database\ClientDALInterface;
use MyCompany\Domain\Core\Services\PaginationService;
use MyCompany\Domain\Entity\Client;
use MyCompany\Infrastructure\Doctrine\FilteringSorterAdapter;

class ClientRepository extends ServiceEntityRepository implements ClientDALInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

    public function getClientsByCompany(
        string $companyId,
        int $page = 1,
        int $limit = 15,
        array $queryParams = []
    ): Paginator {
        $qb = $this->createQueryBuilder('c')
            ->innerJoin('c.company', 'co')
            ->where('co.id = :companyId')
            ->setParameter('companyId', $companyId)
            ->setFirstResult(PaginationService::getFirstResult($page, $limit))
            ->setMaxResults($limit);

        $qb = FilteringSorterAdapter::build($qb, $queryParams, 'c');

        return new Paginator($qb);
    }
}
