<?php

namespace MyCompany\Controller\Company;

use MyCompany\Entity\Company;
use MyCompany\Events\Company\CreateCompanyEvent;
use MyCompany\Events\ValidatorEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/companies')]
class CompanyController
{
    public function __construct(
        private EventDispatcherInterface $eventDispatcher,
        private Security $security
    ) {}

    #[Route('', name: 'create_company', methods: ['POST'])]
    public function createCompany(Request $request): Response
    {
        if ($this->security->getUser()->getCompany() instanceof Company) {
            throw new AccessDeniedHttpException("Vous êtes déjà associé à une compagnie.");
        }
        $event = CreateCompanyEvent::create(json_decode($request->getContent(), true));
        $this->eventDispatcher->dispatch(ValidatorEvent::create($event));
        $event = $this->eventDispatcher->dispatch($event);

        return new Response($event->getResponse(), Response::HTTP_CREATED);
    }
}
