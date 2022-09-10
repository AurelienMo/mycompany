<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Exceptions\BadRequestException;
use MyCompany\Domain\Core\Services\PaginationService;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Product\Exceptions\ProductNotFoundException;
use MyCompany\Domain\Product\UseCases\CreateProductUseCase;
use MyCompany\Domain\Product\UseCases\DeleteProductUseCase;
use MyCompany\Domain\Product\UseCases\GetProductUseCase;
use MyCompany\Domain\Product\UseCases\ListProductUseCase;
use MyCompany\Domain\Product\UseCases\UpdateProductUseCase;
use MyCompany\UI\Adapters\Http\Product\CreateProductHttp;
use MyCompany\UI\Adapters\Http\Product\GetProductHttp;
use MyCompany\UI\Adapters\Http\Product\ListProductsHttp;
use MyCompany\UI\Adapters\Http\Product\UpdateProduct;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/products')]
class ProductController
{
    private const ID = '/{id}';
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

    #[Route(self::ID, name: 'get_product', methods: ['GET'])]
    public function getProduct(string $id, GetProductUseCase $useCase): JsonResponse
    {
        $product = $useCase->execute(new GetProductHttp($id));

        return new JsonResponse(
            $this->normalizer->normalize($product, 'json', ['groups' => ['base', Product::GROUPS_SERIALIZATION_DETAIL]]),
            Response::HTTP_OK
        );
    }

    #[Route("", name: "create_product", methods: ["POST"])]
    public function create(Request $request, CreateProductUseCase $useCase): JsonResponse
    {
        $data = $useCase->execute(new CreateProductHttp(json_decode($request->getContent(), true)));

        return new JsonResponse($data, Response::HTTP_CREATED);
    }

    #[Route(self::ID, name: "delete_product", methods: ["DELETE"])]
    public function delete(string $id, DeleteProductUseCase $useCase): JsonResponse
    {
        $useCase->execute(new GetProductHttp($id));

        return new JsonResponse(
            null,
            Response::HTTP_NO_CONTENT
        );
    }

    #[Route(self::ID, name: "update_product", methods: ["PUT"])]
    public function update(Request $request, string $id, UpdateProductUseCase $useCase): JsonResponse
    {
        $useCase->execute(new UpdateProduct($id, $request->toArray()));

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
