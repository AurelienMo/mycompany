<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Core\Services\PaginationService;
use MyCompany\Domain\Product\UseCases\ListProductUseCase;
use MyCompany\UI\Adapters\Http\Product\ListProductsHttp;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/products')]
class ProductController
{
    public function __construct(private NormalizerInterface $normalizer) {}

    #[Route('', name: 'list_products', methods: ['GET'])]
    public function listProducts(
        Request $request,
        ListProductUseCase $useCase
    ): JsonResponse {
        $paginatorValues = PaginationService::extractValues($request);
        $result = $useCase->execute(new ListProductsHttp($paginatorValues, $request->query->all()));

        return new JsonResponse(
            $this->normalizer->normalize($result, 'json', ['groups' => ['base', 'list:products']]),
            Response::HTTP_OK,
            PaginationService::buildPaginationHeaders($result, $paginatorValues)
        );
    }
}
