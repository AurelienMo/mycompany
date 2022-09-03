<?php

namespace MyCompany\Events\Company;

use MyCompany\Domain\Entity\UserAccount;
use Symfony\Contracts\EventDispatcher\Event;

class GetCompanyEvent extends Event
{
    private UserAccount $userAccount;

    private string $response;

    public function __construct(UserAccount $userAccount)
    {
        $this->userAccount = $userAccount;
    }

    public static function create(UserAccount $userAccount): self
    {
        return new self($userAccount);
    }

    public function getUserAccount(): UserAccount
    {
        return $this->userAccount;
    }

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): void
    {
        $this->response = $response;
    }
}
