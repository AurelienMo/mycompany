<?php

namespace MyCompany\Validators;

use MyCompany\Repository\Interfaces\UserDALInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserConstraintValidator extends ConstraintValidator
{
    public function __construct(private UserDALInterface $userDAL) {}

    public function validate(mixed $value, Constraint $constraint)
    {
        $user = $this->userDAL->getByEmail($value);

        if ($value === null) {
            return;
        }

        if ($user) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
