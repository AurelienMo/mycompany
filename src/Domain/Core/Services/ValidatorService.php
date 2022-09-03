<?php

namespace MyCompany\Domain\Core\Services;

use MyCompany\Domain\Core\Exceptions\BadRequestException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(object $element): void
    {
        $constraintList = $this->validator->validate($element);
        $errors = [];
        if ($constraintList->count() > 0) {
            foreach ($constraintList as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            throw new BadRequestException($errors);
        }
    }
}
