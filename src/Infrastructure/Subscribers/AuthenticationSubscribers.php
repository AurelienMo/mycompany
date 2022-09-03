<?php

namespace MyCompany\Infrastructure\Subscribers;

use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationFailureResponse;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;

class AuthenticationSubscribers
{
    public function __construct(private RefreshTokenGeneratorInterface $refreshTokenGenerator) {}

    public function onJwtExpired(JWTExpiredEvent $event)
    {
        /** @var JWTAuthenticationFailureResponse $response */
        $response = $event->getResponse();
        $response->setMessage("Jeton expiré");
    }

    public function onInvalidCredentials(AuthenticationFailureEvent $event)
    {
        $response = new JWTAuthenticationFailureResponse('Identifiants invalides.', Response::HTTP_UNAUTHORIZED);
        $event->setResponse($response);
    }

    public function onSuccess(AuthenticationSuccessEvent $event)
    {
        $extraData = [
            'id' => $event->getUser()->getId()->toString(),
            'refresh_token' => $this->refreshTokenGenerator->createForUserWithTtl($event->getUser(), 2592000)
        ];

        $event->setData(array_merge($event->getData(), $extraData));
    }
}
