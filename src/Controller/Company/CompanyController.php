<?php

namespace MyCompany\Controller\Company;

use MyCompany\Domain\Entity\Company;
use MyCompany\Events\Company\CreateCompanyEvent;
use MyCompany\Events\Company\GetCompanyEvent;
use MyCompany\Events\ValidatorEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    #[Route('', name: 'get_company_information', methods: ['GET'])]
    public function getCompany(): Response
    {
        $user = $this->security->getUser();
        if (is_null($user->getCompany())) {
            throw new NotFoundHttpException("Aucune compagnie n'a été trouvée.");
        }
        $event = $this->eventDispatcher->dispatch(GetCompanyEvent::create($user));
        return new Response($event->getResponse(), Response::HTTP_OK);
    }

    #[Route('', name: 'update_company', methods: ['PUT'])]
    public function updateCompany(Request $request): Response
    {
        if (is_null($this->security->getUser()->getCompany())) {
            throw new NotFoundHttpException("Aucune compagnie n'a été trouvée.");
        }
        $event = CreateCompanyEvent::create(json_decode($request->getContent(), true));
        $this->eventDispatcher->dispatch(ValidatorEvent::create($event));
        $event = $this->eventDispatcher->dispatch($event);

        return new Response($event->getResponse(), Response::HTTP_NO_CONTENT);
    }
}
