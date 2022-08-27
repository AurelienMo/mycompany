<?php

namespace MyCompany\Controller\User;

use MyCompany\Events\UserAccount\RegistrationEvent;
use MyCompany\Events\ValidatorEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

class SecurityController
{
    public function __construct(private EventDispatcherInterface $eventDispatcher) {}

    #[Route("/registration", name: 'registration_user', methods: ['POST'])]
    public function registration(Request $request): Response
    {
        $event = RegistrationEvent::create(json_decode($request->getContent(), true));
        $this->eventDispatcher->dispatch(ValidatorEvent::create($event));
        $this->eventDispatcher->dispatch($event);

        return new Response($event->getResponse(), Response::HTTP_CREATED);
    }
}
