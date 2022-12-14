<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Core\Exceptions\BadRequestException;
use MyCompany\Domain\User\UseCases\RegistrationUseCase;
use MyCompany\UI\Adapters\Http\User\RegistrationUserHttp;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    #[Route("/registration", name: 'registration_user', methods: ['POST'])]
    public function registration(
        Request $request,
        RegistrationUseCase $registrationUseCase
    ): JsonResponse {
        try {
            $data = $registrationUseCase->execute(new RegistrationUserHttp($request->toArray()));
        } catch (BadRequestException $exception) {
            return new JsonResponse($exception->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($data, Response::HTTP_CREATED);
    }
}
