<?php

namespace MyCompany\Domain\Client\Ports\UseCases;

interface ListClientsDTOInterface
{
    public function getCompanyId(): string;

    public function getPage(): int;

    public function getLimit(): int;

    public function getQueryParams(): array;
}
