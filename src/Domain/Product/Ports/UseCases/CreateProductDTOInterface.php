<?php

namespace MyCompany\Domain\Product\Ports\UseCases;

use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

interface CreateProductDTOInterface
{
    #[NotBlank(message: "Le champ référence est manquant.")]
    public function getRef(): ?string;

    #[NotBlank(message: "Le champ description est manquant.")]
    public function getDescription(): ?string;

    #[NotBlank(message: "Le champ prix unitaire est manquant.")]
    #[GreaterThan(value: 0, message: "Le prix unitaire d'un produit doit être supérieur à 0€.")]
    public function getUnitPrice(): ?float;
}
