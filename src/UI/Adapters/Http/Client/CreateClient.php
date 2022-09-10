<?php

namespace MyCompany\UI\Adapters\Http\Client;

use MyCompany\Domain\Client\Ports\UseCases\CreateClientDTOInterface;

class CreateClient implements CreateClientDTOInterface
{
    private string $companyId;

    private ?string $firstname;

    private ?string $lastname;

    private ?string $email;

    private ?string $streetNumber;

    private ?string $streetName;

    private ?string $zipCode;

    private ?string $city;

    public function __construct(string $companyId, array $payload)
    {
        $this->companyId = $companyId;
        $this->firstname = $payload['firstname'] ?? null;
        $this->lastname = $payload['lastname'] ?? null;
        $this->email = $payload['email'] ?? null;
        $this->streetNumber = $payload['streetNumber'] ?? null;
        $this->streetName = $payload['streetName'] ?? null;
        $this->zipCode = $payload['zipCode'] ?? null;
        $this->city = $payload['city'] ?? null;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getEmail(): ?string
    {
        return $this->email;
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
