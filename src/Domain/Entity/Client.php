<?php

namespace MyCompany\Domain\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

#[Table()]
#[Entity()]
class Client extends AbstractEntity
{
    public const GROUPS_SERIALIZATION_LIST = 'list:clients';

    #[Column(type: 'string', length: 255, nullable: false)]
    #[Groups([self::GROUPS_SERIALIZATION_LIST])]
    private string $firstname;

    #[Column(type: 'string', length: 255, nullable: false)]
    #[Groups([self::GROUPS_SERIALIZATION_LIST])]
    private string $lastname;

    #[Column(type: 'string', length: 255, nullable: false)]
    #[Groups([self::GROUPS_SERIALIZATION_LIST])]
    private string $email;

    #[Column(type: 'string', length: 255, nullable: true)]
    #[Groups([self::GROUPS_SERIALIZATION_LIST])]
    private ?string $streetNumber;

    #[Column(type: 'string', length: 255, nullable: false)]
    #[Groups([self::GROUPS_SERIALIZATION_LIST])]
    private string $streetName;

    #[Column(type: 'string', nullable: false)]
    #[Groups([self::GROUPS_SERIALIZATION_LIST])]
    private string $zipCode;

    #[Column(type: 'string', nullable: false)]
    #[Groups([self::GROUPS_SERIALIZATION_LIST])]
    private string $city;

    #[ManyToOne(targetEntity: Company::class)]
    #[JoinColumn(name: 'company_id', referencedColumnName: 'id')]
    private Company $company;

    public function __construct(
        Company $company,
        ?string $firstname,
        ?string $lastname,
        ?string $email,
        ?string $streetNumber,
        ?string $streetName,
        ?string $zipCode,
        ?string $city
    ) {
        $this->company = $company;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->streetNumber = $streetNumber;
        $this->streetName = $streetName;
        $this->zipCode = $zipCode;
        $this->city = $city;
        parent::__construct();
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function getStreetName(): string
    {
        return $this->streetName;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getCompany(): Company
    {
        return $this->company;
    }
}
