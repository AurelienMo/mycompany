<?php

namespace MyCompany\Validators\Company;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class FirstnameOrCompanyNameConstraint extends Constraint
{
    public $message = "Le couple prénom nom ou nom de la compagnie doit être renseigné.";

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
