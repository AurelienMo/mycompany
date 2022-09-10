<?php

namespace MyCompany\Domain\Client\Ports\UseCases;

use Symfony\Component\Validator\Constraints\NotBlank;

interface CreateClientDTOInterface
{
    public function getCompanyId(): string;

    #[NotBlank(message: "Le prénom du client est requis.")]
    public function getFirstname(): ?string;

    #[NotBlank(message: "Le nom du client est requis.")]
    public function getLastname(): ?string;

    #[NotBlank(message: "L'adresse email du client est requise.")]
    public function getEmail(): ?string;

    public function getStreetNumber(): ?string;

    #[NotBlank(message: "Le nom de la rue est requis.")]
    public function getStreetName(): ?string;

    #[NotBlank(message: "Le code postal est requis.")]
    public function getZipCode(): ?string;

    #[NotBlank(message: "Le ville est requise.")]
    public function getCity(): ?string;
}
