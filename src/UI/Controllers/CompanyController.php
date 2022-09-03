<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Company\UseCases\CreateCompanyUseCase;
use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Exceptions\BadRequestException;
use MyCompany\UI\Adapters\Http\Company\CreateCompanyHttp;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/companies')]
class CompanyController
{
    #[Route('', name: 'create_company', methods: ['POST'])]
    public function createCompany(Request $request, CreateCompanyUseCase $useCase): JsonResponse
    {
        try {
            $data = $useCase->execute(new CreateCompanyHttp($request->toArray()));
        } catch (AccessDeniedException $e) {
            return new JsonResponse(['message' => $e->getMessage()], Response::HTTP_FORBIDDEN);
        } catch (BadRequestException $e) {
            return new JsonResponse($e->getErrors(), Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse($data, Response::HTTP_CREATED);
    }
}
