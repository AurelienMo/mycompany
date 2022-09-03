<?php

namespace MyCompany\Domain\User\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UniqueUserConstraint extends Constraint
{
    public $message = "Compte utilisateur déjà existant.";
}
