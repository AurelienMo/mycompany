<?php

namespace MyCompany\Infrastructure\Doctrine;

use Doctrine\ORM\EntityManagerInterface;
use MyCompany\Domain\Core\Ports\DatabaseInterface;

class DatabaseAdapter implements DatabaseInterface
{
    public function __construct(private EntityManagerInterface $entityManager) {}

    public function save(object $entity = null): void
    {
        if ($entity) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function delete(object $entity): void
    {
        $this->entityManager->remove($entity);
    }
}
