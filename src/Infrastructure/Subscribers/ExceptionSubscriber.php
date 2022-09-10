<?php

namespace MyCompany\Infrastructure\Subscribers;

use Doctrine\ORM\Query\QueryException;
use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Exceptions\BadRequestException;
use MyCompany\Domain\Core\Exceptions\InvalidPaginationArgumentException;
use MyCompany\Domain\Core\Exceptions\NotFoundException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => 'processException',
        ];
    }

    public function processException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $data = null;
        switch(true) {
            case $exception instanceof InvalidPaginationArgumentException:
            case $exception instanceof QueryException:
                $statusCode = Response::HTTP_BAD_REQUEST;
                $data = [
                    'message' => $exception->getMessage()
                ];
                break;
            case $exception instanceof AccessDeniedException:
                $statusCode = Response::HTTP_FORBIDDEN;
                $data = [
                    'message' => $exception->getMessage()
                ];
                break;
            case $exception instanceof NotFoundException:
                $statusCode = Response::HTTP_NOT_FOUND;
                $data = [
                    'message' => $exception->getMessage()
                ];
                break;
            case $exception instanceof BadRequestException:
                $statusCode = Response::HTTP_BAD_REQUEST;
                $data = $exception->getErrors();
                break;
            default;

        }

        if ($exception instanceof InvalidPaginationArgumentException) {
            $data['message'] = $exception->getMessage();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }
        if ($exception instanceof QueryException) {
            $data['message'] = $exception->getMessage();
            $statusCode = Response::HTTP_BAD_REQUEST;
        }

        $this->debugDataDisplay($exception, $data);

        $event->setResponse(new JsonResponse($data, $statusCode));
    }

    private function debugDataDisplay(\Throwable $exception, array &$result): void
    {
        if (getenv('APP_ENV') !== 'prod' && getenv('APP_ENV')) {
            $result['debug'] = $exception;
            $result['trace'] = $exception->getTrace();

            $previous = $exception->getPrevious();
            if ($previous) {
                $result['previous']            = [];
                $result['previous']['message'] = $previous->getMessage();
                $result['previous']['trace']   = $previous->getTrace();
            }
        }
    }
}
