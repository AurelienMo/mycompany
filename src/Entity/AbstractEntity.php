<?php

namespace MyCompany\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

abstract class AbstractEntity
{
    #[Id()]
    #[Column(name: "id", type: "uuid")]
    #[GeneratedValue(strategy: "NONE")]
    #[Groups("base")]
    protected UuidInterface $id;

    #[Column(name: "created_at", type: "datetime")]
    #[Groups("base")]
    protected \DateTime $createdAt;

    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->createdAt = new \DateTime();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
