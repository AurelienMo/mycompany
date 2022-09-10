<?php

namespace MyCompany\UI\Controllers;

use MyCompany\Domain\Company\Exceptions\CompanyNotFoundException;
use MyCompany\Domain\Company\UseCases\CreateCompanyUseCase;
use MyCompany\Domain\Company\UseCases\GetCompanyUseCase;
use MyCompany\Domain\Core\Exceptions\AccessDeniedException;
use MyCompany\Domain\Core\Exceptions\BadRequestException;
use MyCompany\Domain\Entity\Company;
use MyCompany\UI\Adapters\Http\Company\CreateCompanyHttp;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

#[Route('/companies')]
class CompanyController
{
    public function __construct(private NormalizerInterface $normalizer) {}

    #[Route('', name: 'create_company', methods: ['POST', 'PUT'])]
    public function createOrUpdateCompany(Request $request, CreateCompanyUseCase $useCase): JsonResponse
    {
        $data = $useCase->execute(new CreateCompanyHttp($request->toArray()), $request->getMethod());

        return new JsonResponse(
            $request->getMethod() === 'POST' ? $data : null,
            $request->getMethod() === 'POST' ? Response::HTTP_CREATED : Response::HTTP_NO_CONTENT
        );
    }

    #[Route('', name: 'get_company_information', methods: ['GET'])]
    public function getCompany(GetCompanyUseCase $useCase): JsonResponse
    {
        $data = $useCase->execute();

        return new JsonResponse(
            $this->normalizer->normalize(
                $data,
                'json',
                ['groups' => ['base', Company::GROUP_SERIALIZATION_COMPANY_DETAIL]]),
            Response::HTTP_OK
        );
    }
}
