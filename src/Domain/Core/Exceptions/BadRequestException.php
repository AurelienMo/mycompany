<?php

namespace MyCompany\Domain\Core\Exceptions;

class BadRequestException extends \Exception
{
    private array $errors;

    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct("", 0, null);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
