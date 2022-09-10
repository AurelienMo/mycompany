<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Client\UseCases\CreateClientUseCase;
use MyCompany\UI\Adapters\Http\Client\CreateClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route("/companies/{companyId}/clients")]
class ClientController
{
    #[Route("", name: "create_client", methods: ["POST"])]
    public function create(
        Request $request,
        string $companyId,
        CreateClientUseCase $useCase
    ): JsonResponse
    {
        $clientId = $useCase->execute(
            new CreateClient($companyId, $request->toArray())
        );

        return new JsonResponse(['id' => $clientId], Response::HTTP_CREATED);
    }
}
