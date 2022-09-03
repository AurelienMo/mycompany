<?php

namespace MyCompany\Tests\Controller\Company;

use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Tests\AbstractWebtestCase;

class CreateCompanyTest extends AbstractWebtestCase
{
    public function testWithMissingRequiredField()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $this->logUser('john@doe.com');
        $response = $this->postRequest('/api/companies', []);
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
        $expectedResponse = [
            'streetNumber' => [
                "Le numéro de rue est requis."
            ],
            'streetName' => [
                "Le numéro de rue est requis."
            ],
            'zipCode' => [
                "Le code postal est requis."
            ],
            'city' => [
                "La ville est requise."
            ],
            'freelance' => [
                "Vous devez renseigner si vous êtes freelance ou non."
            ]
        ];
        $this->assertEquals($expectedResponse, $content);
    }

    public function testWithZipCodeTooLength()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $this->logUser("john@doe.com");
        $response = $this->postRequest(
            '/api/companies',
            [
                'streetNumber' => '1',
                'streetName' => 'rue de la paix',
                'zipCode' => '7500000',
                'city' => 'Paris',
                'isFreelance' => false,
                'companyName' => 'Une compagnie'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
        $expectedResponse = [
            'zipCode' => [
                'Le code postal ne doit pas faire plus de 5 caractères.'
            ]
        ];
        $this->assertEquals($expectedResponse, $content);
    }

    public function testWithUserAlreadyHaveCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser("john@doe.com");
        $response = $this->postRequest(
            '/api/companies',
            [
                'streetNumber' => '1',
                'streetName' => 'rue de la paix',
                'zipCode' => '75000',
                'city' => 'Paris',
                'isFreelance' => false,
                'companyName' => 'Une compagnie'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals("Vous êtes déjà associé à une compagnie.", $content['message']);
    }

    public function testWithUnauthorizedUser()
    {
        $response = $this->postRequest(
            '/api/companies',
            [
                'streetNumber' => '1',
                'streetName' => 'rue de la paix',
                'zipCode' => '75000',
                'city' => 'Paris',
                'isFreelance' => false,
                'companyName' => 'Une compagnie'
            ]
        );
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testSuccessCreateCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $this->logUser('john@doe.com');
        $response = $this->postRequest(
            '/api/companies',
            [
                'streetNumber' => '1',
                'streetName' => 'rue de la paix',
                'zipCode' => '75000',
                'city' => 'Paris',
                'isFreelance' => false,
                'companyName' => 'Une compagnie'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $content);
        $company = $this->entityManager->getRepository(Company::class)->findOneBy(['companyName' => 'Une compagnie']);
        $this->assertInstanceOf(Company::class, $company);
        $user = $this->entityManager->getRepository(UserAccount::class)->findOneBy(['email' => 'john@doe.com']);
        $this->assertInstanceOf(Company::class, $user->getCompany());
        $this->assertEquals($company->getId()->toString(), $user->getCompany()->getId()->toString());
    }
}
