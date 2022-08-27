<?php

namespace MyCompany\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Table(name: "user_account")]
#[Entity]
class UserAccount extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Column(name: "email", type: "string")]
    private string $email;

    #[Column(name: "password", type: "string")]
    private string $password;

    public function __construct(string $email)
    {
        $this->email = $email;
        parent::__construct();
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function eraseCredentials()
    {
        return;
    }

    public function getUserIdentifier(): string
    {
        return 'email';
    }

    public function defineCredentials(string $password): void
    {
        $this->password = $password;
    }
}
