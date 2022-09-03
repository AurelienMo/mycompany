<?php

namespace MyCompany\Infrastructure\Security;

use Gesdinet\JWTRefreshTokenBundle\Generator\RefreshTokenGeneratorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordHasherService implements PasswordSecurityInterface
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private JWTTokenManagerInterface $jwtTokenManager,
        private RefreshTokenGeneratorInterface $refreshTokenManager
    ) {}

    public function hash(UserAccount $user, string $password): string
    {
        return $this->passwordHasher->hashPassword($user, $password);
    }

    public function generateToken(UserAccount $user): string
    {
        return $this->jwtTokenManager->create($user);
    }

    public function generateRefreshToken(UserAccount $user): string
    {
        return $this->refreshTokenManager->createForUserWithTtl($user, 2592000)->getRefreshToken();
    }
}
