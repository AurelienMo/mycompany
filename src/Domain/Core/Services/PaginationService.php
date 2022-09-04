<?php

namespace MyCompany\Domain\Core\Services;

use Doctrine\ORM\Tools\Pagination\Paginator;
use MyCompany\Domain\Core\Exceptions\InvalidPaginationArgumentException;
use MyCompany\Domain\Core\Services\Pagination\PaginationValues;
use Symfony\Component\HttpFoundation\Request;

class PaginationService
{
    const DEFAULT_LIMIT   = 15;
    const MAX_LIMIT_VALUE = 100;

    /**
     * Extract pagination values from Request
     *
     * @param Request $request
     *
     * @return PaginationValues
     */
    public static function extractValues(Request $request): PaginationValues
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::DEFAULT_LIMIT);

        if (!is_numeric($page) || !is_numeric($limit)) {
            throw new InvalidPaginationArgumentException("Pagination values provided must be an integer");
        }

        if ($page < 1) {
            throw new InvalidPaginationArgumentException("Pagination page provided must be greater than 0");
        }

        if ($limit < 1 || $limit > self::MAX_LIMIT_VALUE) {
            throw new InvalidPaginationArgumentException(
                sprintf(
                    "Pagination limit provided must be between 1 and %d",
                self::MAX_LIMIT_VALUE
                )
            );
        }

        return new PaginationValues($page, $limit);
    }

    /**
     * Build the pagination header.
     *
     * @param Paginator       $paginator
     *
     * @param PaginationValues $paginatorValues
     *
     * @return array
     */
    public static function buildPaginationHeaders(Paginator $paginator, PaginationValues $paginatorValues)
    {
        $totalPage = (int) ceil(count($paginator) / $paginatorValues->getLimit());
        $currentPage = $paginatorValues->getPage();
        return [
            'Pagination-Page'  => $currentPage,
            'Pagination-Count' => (int) ceil(count($paginator) / $paginatorValues->getLimit()),
            'Element-Total'    => (int) $paginator->count(),
            'Pagination-Limit' => (int) $paginatorValues->getLimit(),
            'Pagination-Has-Next' => $currentPage < $totalPage ? "1" : "0",
            'Pagination-Has-Previous' => $currentPage > 1 ? "1" : "0",
        ];
    }

    public static function getFirstResult(int $page, int $limit): int
    {
        return ($page * $limit) - $limit;
    }
}
