<?php

namespace MyCompany\Infrastructure\Doctrine;

use Doctrine\ORM\QueryBuilder;

class FilteringSorterAdapter
{
    public static function build(QueryBuilder $queryBuilder, array $queryParams, string $alias): QueryBuilder
    {
        unset($queryParams['page'], $queryParams['limit']);
        if (array_key_exists('sort', $queryParams)) {
            $sortParams = explode(',', $queryParams['sort']);
            $sortDirection = array_key_exists('sortDirection', $queryParams) ? $queryParams['sortDirection'] : 'ASC';
            foreach ($sortParams as $sortParam) {
                $queryBuilder->addOrderBy(sprintf('%s.%s', $alias, $sortParam), $sortDirection);
            }
        }
        unset($queryParams['sort']);
        foreach ($queryParams as $property => $value) {
            $queryBuilder->andWhere(sprintf("%s.%s LIKE :param", $alias, $property))
                         ->setParameter('param', '%' . $value . '%');
        }

        return $queryBuilder;
    }
}
