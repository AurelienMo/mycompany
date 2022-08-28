<?php

namespace MyCompany\Events;

use Symfony\Contracts\EventDispatcher\Event;

class SerializationEvent extends Event
{
    private array|object $data;

    private array $groups;

    private string $resultSerialization;

    public function __construct(array|object $data, array $groups)
    {
        $this->data = $data;
        $this->groups = $groups;
    }

    public static function create(array|object $data, array $groups): self
    {
        return new self($data, $groups);
    }

    public function getData(): object|array
    {
        return $this->data;
    }

    public function getGroups(): array
    {
        return $this->groups;
    }

    public function getResultSerialization(): string
    {
        return $this->resultSerialization;
    }

    public function setResultSerialization(string $resultSerialization): void
    {
        $this->resultSerialization = $resultSerialization;
    }
}
