<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Exceptions\BadRequestException;
use MyCompany\Domain\Core\Services\PaginationService;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Product\Exceptions\ProductNotFoundException;
use MyCompany\Domain\Product\UseCases\CreateProductUseCase;
use MyCompany\Domain\Product\UseCases\GetProductUseCase;
use MyCompany\Domain\Product\UseCases\ListProductUseCase;
use MyCompany\UI\Adapters\Http\Product\CreateProductHttp;
use MyCompany\UI\Adapters\Http\Product\GetProductHttp;
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
            $this->normalizer->normalize($result, 'json', ['groups' => ['base', Product::GROUPS_SERIALIZATION_LIST]]),
            Response::HTTP_OK,
            PaginationService::buildPaginationHeaders($result, $paginatorValues)
        );
    }

    #[Route("/{id}", name: 'get_product', methods: ['GET'])]
    public function getProduct(string $id, GetProductUseCase $useCase): JsonResponse
    {
        try {
            $product = $useCase->execute(new GetProductHttp($id));
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (AccessDeniedException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        }

        return new JsonResponse(
            $this->normalizer->normalize($product, 'json', ['groups' => ['base', Product::GROUPS_SERIALIZATION_DETAIL]]),
            Response::HTTP_OK
        );
    }

    #[Route("", name: "create_product", methods: ["POST"])]
    public function create(Request $request, CreateProductUseCase $useCase): JsonResponse
    {
        try {
            $data = $useCase->execute(new CreateProductHttp(json_decode($request->getContent(), true)));
        } catch (AccessDeniedException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (BadRequestException $e) {
            return new JsonResponse($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($data, Response::HTTP_CREATED);
    }
}
