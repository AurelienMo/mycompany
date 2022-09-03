<?php

namespace MyCompany\Domain\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use MyCompany\Domain\Company\Ports\UseCases\CreateCompanyDTOInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[Table(name: "company")]
#[Entity]
class Company extends AbstractEntity
{
    public const GROUP_SERIALIZATION_COMPANY_DETAIL = 'company:detail';

    #[Column(name: "firstname", type: "string", nullable: true)]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private string|null $firstname;

    #[Column(name: "lastname", type: "string", nullable: true)]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private string|null $lastname;

    #[Column(name: "company_name", type: "string", nullable: true)]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private string|null $companyName;

    #[Column(name: "is_freelance", type: "boolean")]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private bool $isFreelance;

    #[Column(name: "vat_number", type: "string", nullable: true)]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private string|null $vatNumber;

    #[Column(name: "street_number", type: "string")]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private string $streetNumber;

    #[Column(name: "street_name", type: "string")]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private string $streetName;

    #[Column(name: "zip_code", type: "string")]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
    private string $zipCode;

    #[Column(name: "city", type: "string")]
    #[Groups(self::GROUP_SERIALIZATION_COMPANY_DETAIL)]
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

    public function update(CreateCompanyDTOInterface $dto): void
    {
        $this->firstname = $dto->getFirstname();
        $this->lastname = $dto->getLastname();
        $this->companyName = $dto->getCompanyName();
        $this->isFreelance = $dto->isFreelance();
        $this->vatNumber = $dto->getVatNumber();
        $this->streetNumber = $dto->getStreetNumber();
        $this->streetName = $dto->getStreetName();
        $this->zipCode = $dto->getZipCode();
        $this->city = $dto->getCity();
    }
}
