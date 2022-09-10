<?php

namespace MyCompany\Domain\Core\Ports;

interface DatabaseInterface
{
    public function save(object $entity = null): void;

    public function delete(object $entity): void;
}
