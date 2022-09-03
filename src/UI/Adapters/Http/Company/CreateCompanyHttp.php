<?php

namespace MyCompany\UI\Adapters\Http\Company;

use MyCompany\Domain\Company\Ports\UseCases\CreateCompanyDTOInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

class CreateCompanyHttp implements CreateCompanyDTOInterface
{
    private ?string $firstname;
    private ?string $lastname;
    private ?string $companyName;
    private ?bool $isFreelance;
    private ?string $vatNumber;
    private ?string $streetNumber;
    private ?string $streetName;
    private ?string $zipCode;
    private ?string $city;

    public function __construct(array $payload)
    {
        $this->firstname = $payload['firstname'] ?? null;
        $this->lastname = $payload['lastname'] ?? null;
        $this->companyName = $payload['companyName'] ?? null;
        $this->isFreelance = $payload['isFreelance'] ?? null;
        $this->vatNumber = $payload['vatNumber'] ?? null;
        $this->streetNumber = $payload['streetNumber'] ?? null;
        $this->streetName = $payload['streetName'] ?? null;
        $this->zipCode = $payload['zipCode'] ?? null;
        $this->city = $payload['city'] ?? null;
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

    public function isFreelance(): ?bool
    {
        return $this->isFreelance;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function getStreetNumber(): ?string
    {
        return $this->streetNumber;
    }

    public function getStreetName(): ?string
    {
        return $this->streetName;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

}
