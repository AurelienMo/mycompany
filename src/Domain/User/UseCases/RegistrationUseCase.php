<?php

namespace MyCompany\Domain\User\UseCases;

use MyCompany\Domain\Core\Exceptions\BadRequestException;
use MyCompany\Domain\Core\Ports\DatabaseInterface;
use MyCompany\Domain\Core\Services\ValidatorService;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Domain\Security\Ports\PasswordSecurityInterface;
use MyCompany\Domain\User\Ports\UseCases\RegistrationDTOInterface;

class RegistrationUseCase
{
    public function __construct(
        private ValidatorService $validator,
        private PasswordSecurityInterface $passwordSecurity,
        private DatabaseInterface $database
    ) {}

    /**
     * @param RegistrationDTOInterface $dto
     *
     * @return array
     *
     * @throws BadRequestException
     */
    public function execute(RegistrationDTOInterface $dto): array
    {
        $this->validator->validate($dto);
        $user = new UserAccount($dto->getEmail());
        $passwordHash = $this->passwordSecurity->hash($user, $dto->getPassword());
        $user->defineCredentials($passwordHash);
        $this->database->save($user);

        return [
            'id' => $user->getId()->toString(),
            'token' => $this->passwordSecurity->generateToken($user),
            'refresh_token' => $this->passwordSecurity->generateRefreshToken($user)
        ];
    }

}
