<?php

namespace MyCompany\Events\UserAccount;

use MyCompany\Validators\UniqueUserConstraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\EventDispatcher\Event;

class RegistrationEvent extends Event
{
    #[NotBlank(message: "L'adresse email est requise.")]
    #[Email(message: "Format invalide.")]
    #[UniqueUserConstraint()]
    private ?string $email;

    #[NotBlank(message: "Le mot de passe est requis.")]
    #[Length(min: 8, minMessage: "La longueur du mot de passe doit contenir au moins 8 caractères.")]
    private ?string $password;

    private string $response;

    public function __construct(array $payload)
    {
        $this->email = $payload['email'] ?? null;
        $this->password = $payload['password'] ?? null;
    }

    final public function getEmail(): mixed
    {
        return $this->email;
    }

    final public function getPassword(): mixed
    {
        return $this->password;
    }

    public static function create(array $payload): RegistrationEvent
    {
        return new self($payload);
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
