<?php

namespace MyCompany\Subscribers;

use MyCompany\Events\SerializationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\SerializerInterface;

class SerializationSubscriber implements EventSubscriberInterface
{
    public function __construct(private SerializerInterface $serializer) {}

    public static function getSubscribedEvents()
    {
        return [
            SerializationEvent::class => 'processSerialization',
        ];
    }

    public function processSerialization(SerializationEvent $event): void
    {
        $event->setResultSerialization(
            $this->serializer->serialize($event->getData(), 'json', ['groups' => $event->getGroups()])
        );
    }
}
