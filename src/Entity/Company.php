<?php

namespace MyCompany\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Table(name: "company")]
#[Entity]
class Company extends AbstractEntity
{
    #[Column(name: "firstname", type: "string", nullable: true)]
    private string|null $firstname;

    #[Column(name: "lastname", type: "string", nullable: true)]
    private string|null $lastname;

    #[Column(name: "company_name", type: "string", nullable: true)]
    private string|null $companyName;

    #[Column(name: "is_freelance", type: "boolean")]
    private bool $isFreelance;

    #[Column(name: "vat_number", type: "string", nullable: true)]
    private string|null $vatNumber;

    #[Column(name: "street_number", type: "string")]
    private string $streetNumber;

    #[Column(name: "street_name", type: "string")]
    private string $streetName;

    #[Column(name: "zip_code", type: "string")]
    private string $zipCode;

    #[Column(name: "city", type: "string")]
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
}
