<?php

namespace MyCompany\Domain\User\Ports\UseCases;

use MyCompany\Domain\User\Validator\UniqueUserConstraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

interface RegistrationDTOInterface
{
    #[NotBlank(message: "L'adresse email est requise.")]
    #[Email(message: "Format invalide.")]
    #[UniqueUserConstraint()]
    public function getEmail(): ?string;

    #[NotBlank(message: "Le mot de passe est requis.")]
    #[Length(min: 8, minMessage: "La longueur du mot de passe doit contenir au moins 8 caractères.")]
    public function getPassword(): ?string;
}
