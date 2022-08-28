<?php

namespace MyCompany\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Serializer\Annotation\Groups;

#[Table(name: "company")]
#[Entity]
class Company extends AbstractEntity
{
    #[Column(name: "firstname", type: "string", nullable: true)]
    #[Groups("company:detail")]
    private string|null $firstname;

    #[Column(name: "lastname", type: "string", nullable: true)]
    #[Groups("company:detail")]
    private string|null $lastname;

    #[Column(name: "company_name", type: "string", nullable: true)]
    #[Groups("company:detail")]
    private string|null $companyName;

    #[Column(name: "is_freelance", type: "boolean")]
    #[Groups("company:detail")]
    private bool $isFreelance;

    #[Column(name: "vat_number", type: "string", nullable: true)]
    #[Groups("company:detail")]
    private string|null $vatNumber;

    #[Column(name: "street_number", type: "string")]
    #[Groups("company:detail")]
    private string $streetNumber;

    #[Column(name: "street_name", type: "string")]
    #[Groups("company:detail")]
    private string $streetName;

    #[Column(name: "zip_code", type: "string")]
    #[Groups("company:detail")]
    private string $zipCode;

    #[Column(name: "city", type: "string")]
    #[Groups("company:detail")]
    private string $city;

    public function __construct(
        ?string $firstname,
        ?string $lastname,
        ?string $companyName,
        bool $isFreelance,
        ?string $vatNumber,
        string $streetNumber,
        string $streetName,
        string $zipCode,
        string $city
    ) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->companyName = $companyName;
        $this->isFreelance = $isFreelance;
        $this->vatNumber = $vatNumber;
        $this->streetNumber = $streetNumber;
        $this->streetName = $streetName;
        $this->zipCode = $zipCode;
        $this->city = $city;
        parent::__construct();
    }

    public static function create(
        ?string $firstname,
        ?string $lastname,
        ?string $companyName,
        bool $isFreelance,
        ?string $vatNumber,
        string $streetNumber,
        string $streetName,
        string $zipCode,
        string $city
    ) {
        return new self(
            $firstname,
            $lastname,
            $companyName,
            $isFreelance,
            $vatNumber,
            $streetNumber,
            $streetName,
            $zipCode,
            $city
        );
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getCompanyName(): ?string
    {
        return $this->companyName;
    }

    public function isFreelance(): bool
    {
        return $this->isFreelance;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function getStreetNumber(): string
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
}
