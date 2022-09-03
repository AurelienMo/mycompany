<?php

namespace MyCompany\Domain\Company\Ports\UseCases;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;

interface CreateCompanyDTOInterface
{
    public function getFirstname(): ?string;

    public function getLastname(): ?string;

    public function getCompanyName(): ?string;

    #[NotNull(message: "Vous devez renseigner si vous êtes freelance ou non.")]
    public function isFreelance(): ?bool;

    public function getVatNumber(): ?string;

    #[NotBlank(message: "Le numéro de rue est requis.")]
    public function getStreetNumber(): ?string;

    #[NotBlank(message: "Le numéro de rue est requis.")]
    public function getStreetName(): ?string;

    #[NotBlank(message: "Le code postal est requis.")]
    #[Length(min: 5, max: 5, exactMessage: "Le code postal ne doit pas faire plus de 5 caractères.")]
    public function getZipCode(): ?string;

    #[NotBlank(message: "La ville est requise.")]
    public function getCity(): ?string;
}
