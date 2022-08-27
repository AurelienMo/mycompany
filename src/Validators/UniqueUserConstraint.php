<?php

namespace MyCompany\Validators;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueUserConstraint extends Constraint
{
    public $message = "Compte utilisateur déjà existant.";
}
