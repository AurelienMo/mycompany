<?php

namespace MyCompany\Subscribers;

use MyCompany\Exceptions\BadRequestException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'processException',
            KernelEvents::RESPONSE => 'processResponse'
        ];
    }

    public function processException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $data = ['message' => $exception->getMessage()];
        switch (true) {
            case $exception instanceof BadRequestException:
                $statusCode = Response::HTTP_BAD_REQUEST;
                $data = $exception->getErrors();
                break;
        }

        $event->setResponse(new JsonResponse($data, $statusCode));
    }

    public function processResponse(ResponseEvent $event): void
    {
        $response = $event->getResponse();
        $response->headers->set('Content-Type', 'application/json');
        $event->setResponse($response);
    }
}
