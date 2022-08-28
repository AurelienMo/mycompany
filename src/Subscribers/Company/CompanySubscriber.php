<?php

namespace MyCompany\Subscribers\Company;

use Doctrine\ORM\EntityManagerInterface;
use MyCompany\Entity\Company;
use MyCompany\Events\Company\CreateCompanyEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Security;

class CompanySubscriber implements EventSubscriberInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $entityManager
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            CreateCompanyEvent::class => 'processCreateCompanyEvent'
        ];
    }

    public function processCreateCompanyEvent(CreateCompanyEvent $event): void
    {
        $company = Company::create(
            $event->getFirstname(),
            $event->getLastname(),
            $event->getCompanyName(),
            $event->isFreelance(),
            $event->getVatNumber(),
            $event->getStreetNumber(),
            $event->getStreetName(),
            $event->getZipCode(),
            $event->getCity()
        );

        $this->entityManager->persist($company);
        $this->security->getUser()->attachCompany($company);
        $this->entityManager->flush();

        $event->setResponse(json_encode(['id' => $company->getId()->toString()]));
    }
}
