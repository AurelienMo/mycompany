<?php

namespace MyCompany\Domain\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
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

    #[ManyToOne(targetEntity: Company::class)]
    #[JoinColumn(name: "company_id", referencedColumnName: "id", nullable: true)]
    private Company|null $company = null;

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
        return $this->email;
    }

    public function defineCredentials(string $password): void
    {
        $this->password = $password;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function attachCompany(Company $company): void
    {
        $this->company = $company;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
