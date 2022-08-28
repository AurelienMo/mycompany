<?php

namespace MyCompany\Validators\Company;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class FirstnameOrCompanyNameConstraintValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if ($value->getFirstname() === null && $value->getLastname() === null &&
            $value->getCompanyName() === null
        ) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
