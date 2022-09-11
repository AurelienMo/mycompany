<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Client\UseCases\CreateClientUseCase;
use MyCompany\Domain\Client\UseCases\ListClientsUseCase;
use MyCompany\Domain\Core\Services\PaginationService;
use MyCompany\Domain\Entity\Client;
use MyCompany\UI\Adapters\Http\Client\CreateClient;
use MyCompany\UI\Adapters\Http\Client\ListClients;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route("/companies/{companyId}/clients")]
class ClientController
{
    public function __construct(private NormalizerInterface $normalizer) {}

    #[Route("", name: "create_client", methods: ["POST"])]
    public function create(
        Request $request,
        string $companyId,
        CreateClientUseCase $useCase
    ): JsonResponse {
        $clientId = $useCase->execute(
            new CreateClient($companyId, $request->toArray())
        );

        return new JsonResponse(['id' => $clientId], Response::HTTP_CREATED);
    }

    #[Route("", name: "list_clients", methods: ["GET"])]
    public function listClients(Request $request, string $companyId, ListClientsUseCase $useCase): JsonResponse
    {
        $paginatorValues = PaginationService::extractValues($request);
        $result = $useCase->execute(
            new ListClients($companyId, $paginatorValues, $request->query->all())
        );

        return new JsonResponse(
            $this->normalizer->normalize(
                $result,
                'json',
                ['groups' => ['base', Client::GROUPS_SERIALIZATION_LIST]]
            ),
            Response::HTTP_OK,
            PaginationService::buildPaginationHeaders($result, $paginatorValues)
        );
    }
}
