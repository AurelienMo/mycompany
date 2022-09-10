<?php

namespace MyCompany\UI\Adapters\Http\User;

use MyCompany\Domain\User\Ports\UseCases\RegistrationDTOInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationUserHttp implements RegistrationDTOInterface
{
    private string|null $email;

    private string|null $password;

    public function __construct(array $payload)
    {
        $this->email = $payload['email'] ?? null;
        $this->password = $payload['password'] ?? null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
