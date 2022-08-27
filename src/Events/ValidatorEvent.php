<?php

namespace MyCompany\Events;

use Symfony\Contracts\EventDispatcher\Event;

class ValidatorEvent extends Event
{
    private $element;

    public function __construct($element)
    {
        $this->element = $element;
    }

    public function getElement()
    {
        return $this->element;
    }

    public static function create($element)
    {
        return new self($element);
    }
}
