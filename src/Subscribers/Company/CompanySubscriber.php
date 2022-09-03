<?php

namespace MyCompany\Subscribers\Company;

use Doctrine\ORM\EntityManagerInterface;
use MyCompany\Domain\Entity\Company;
use MyCompany\Events\Company\CreateCompanyEvent;
use MyCompany\Events\Company\GetCompanyEvent;
use MyCompany\Events\SerializationEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class CompanySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            GetCompanyEvent::class => 'processGetCompanyInformation'
        ];
    }

    public function processGetCompanyInformation(GetCompanyEvent $event): void
    {
        $serializationEvent = $this->eventDispatcher->dispatch(
            SerializationEvent::create($event->getUserAccount()->getCompany(), ['base', 'company:detail'])
        );

        $event->setResponse($serializationEvent->getResultSerialization());
    }
}
