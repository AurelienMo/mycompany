<?php

namespace MyCompany\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use MyCompany\Domain\Core\Services\PaginationService;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Domain\Product\Ports\Database\ProductDALInterface;
use MyCompany\Infrastructure\Doctrine\FilteringSorterAdapter;

class ProductRepository extends ServiceEntityRepository implements ProductDALInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function listProductsRelatedAuthUser(UserAccount $user, int $page = 1, int $limit = 15, array $queryParams = []): Paginator
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.company', 'c')
            ->where('c.id = :companyId')
            ->setParameter('companyId', $user->getCompany()->getId()->toString())
            ->setFirstResult(PaginationService::getFirstResult($page, $limit))
            ->setMaxResults($limit);
        $qb = FilteringSorterAdapter::build($qb, $queryParams, 'p');

        return new Paginator($qb);
    }

    public function getProduct(string $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
