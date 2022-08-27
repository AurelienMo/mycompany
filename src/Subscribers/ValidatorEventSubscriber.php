<?php

namespace MyCompany\Subscribers;

use MyCompany\Events\ValidatorEvent;
use MyCompany\Exceptions\BadRequestException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorEventSubscriber implements EventSubscriberInterface
{
    public function __construct(private ValidatorInterface $validator){}

    public static function getSubscribedEvents()
    {
        return [
            ValidatorEvent::class => 'onValidateEvent',
        ];
    }

    public function onValidateEvent($event): void
    {
        $constraintList = $this->validator->validate($event->getelement());
        $errors = [];
        if ($constraintList->count() > 0) {
            foreach ($constraintList as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            throw new BadRequestException($errors);
        }
    }
}
