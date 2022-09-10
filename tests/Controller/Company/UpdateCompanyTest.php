<?php

namespace MyCompany\Tests\Controller\Company;

use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Tests\AbstractWebtestCase;

class UpdateCompanyTest extends AbstractWebtestCase
{
    public function testWithUnauthorizedUser()
    {
        $response = $this->putRequest(
            'api/companies',
            []
        );

        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testWithUserHaventCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $this->logUser('john@doe.com');
        $response = $this->putRequest(
            'api/companies',
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
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Aucune compagnie n'a été trouvée.", $content['message']);
    }

    public function testWithMissingRequiredField()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser('john@doe.com');
        $response = $this->putRequest(
            'api/companies',
            []
        );
        $content = $this->getContentResponse($response->getContent());
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
            ],
        ];
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($expectedResponse, $content);
    }

    public function testWithZipCodeTooLength()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser('john@doe.com');
        $response = $this->putRequest(
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

    public function testSuccessUpdateCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser('john@doe.com');
        $response = $this->putRequest(
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
        $this->assertEquals(204, $response->getStatusCode());
        $user = $this->entityManager->getRepository(UserAccount::class)->findOneBy(['email' => 'john@doe.com']);
        $company = $user->getCompany();
        $this->assertEquals('1', $company->getStreetNumber());
        $this->assertEquals('rue de la paix', $company->getStreetName());
        $this->assertEquals('75000', $company->getZipCode());
        $this->assertEquals('Paris', $company->getCity());
        $this->assertEquals(false, $company->isFreelance());
        $this->assertEquals('Une compagnie', $company->getCompanyName());
    }
}
