<?php

namespace MyCompany\Subscribers\UserAccount;

use Doctrine\ORM\EntityManagerInterface;
use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Gesdinet\JWTRefreshTokenBundle\Model\RefreshTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use MyCompany\Entity\UserAccount;
use MyCompany\Events\UserAccount\RegistrationEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAccountSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $jwtTokenManager,
        private RefreshTokenGeneratorInterface $refreshTokenManager
    ) {}

    public static function getSubscribedEvents()
    {
        return [
            RegistrationEvent::class => 'onRegistration',
        ];
    }

    public function onRegistration(RegistrationEvent $event): void
    {
        $user = new UserAccount($event->getEmail());
        $passwordHash = $this->passwordHasher->hashPassword($user, $event->getPassword());
        $user->defineCredentials($passwordHash);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $token = $this->jwtTokenManager->create($user);
        $refreshToken = $this->refreshTokenManager->createForUserWithTtl($user, 2592000);
        $event->setResponse(
            json_encode([
                'id' => $user->getId()->toString(),
                'token' => $token,
                'refresh_token' => $refreshToken->getRefreshToken()
            ])
        );
    }
}
