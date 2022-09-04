<?php

namespace MyCompany\Domain\Core\Services\Pagination;

final class PaginationValues
{
    private int $page;

    private $limit;

    /**
     *
     * PaginatorValues constructor.
     * @param int $page
     * @param int $limit
     */
    public function __construct(int $page, int $limit)
    {
        $this->page = $page;
        $this->limit = $limit;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }
}
