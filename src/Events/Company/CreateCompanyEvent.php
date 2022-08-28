<?php

namespace MyCompany\Events\Company;

use MyCompany\Validators\Company\FirstnameOrCompanyNameConstraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\EventDispatcher\Event;

#[FirstnameOrCompanyNameConstraint]
class CreateCompanyEvent extends Event
{
    private string|null $firstname;

    private string|null $lastname;

    private string|null $companyName;

    #[NotNull(message: "Vous devez renseigner si vous êtes freelance ou non.")]
    private bool|null $isFreelance;

    private string|null $vatNumber;

    #[NotBlank(message: "Le numéro de rue est requis.")]
    private string|null $streetNumber;

    #[NotBlank(message: "Le numéro de rue est requis.")]
    private string|null $streetName;

    #[NotBlank(message: "Le code postal est requis.")]
    #[Length(min: 5, max: 5, exactMessage: "Le code postal ne doit pas faire plus de 5 caractères.")]
    private string|null $zipCode;

    #[NotBlank(message: "La ville est requise.")]
    private string|null $city;

    private string $response;

    public static function create(array $payload): CreateCompanyEvent
    {
        $self = new self();
        $self->firstname = $payload['firstname'] ?? null;
        $self->lastname = $payload['lastname'] ?? null;
        $self->companyName = $payload['companyName'] ?? null;
        $self->isFreelance = array_key_exists('isFreelance', $payload) ? $payload['isFreelance'] : null;
        $self->vatNumber = $payload['vatNumber'] ?? null;
        $self->streetNumber = $payload['streetNumber'] ?? null;
        $self->streetName = $payload['streetName'] ?? null;
        $self->zipCode = $payload['zipCode'] ?? null;
        $self->city = $payload['city'] ?? null;

        return $self;
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

    public function getResponse(): string
    {
        return $this->response;
    }

    public function setResponse(string $response): void
    {
        $this->response = $response;
    }
}
