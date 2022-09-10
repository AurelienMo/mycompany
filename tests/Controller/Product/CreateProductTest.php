<?php

namespace MyCompany\Tests\Controller\Product;

use MyCompany\Tests\AbstractWebtestCase;

class CreateProductTest extends AbstractWebtestCase
{
    public function testWithUnauthorizedUser()
    {
        $response = $this->postRequest(
            '/api/products',
            []
        );
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testWithUserHaventCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $this->logUser("john@doe.com");
        $response = $this->postRequest(
            '/api/products',
            []
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals("Vous devez être associé à une compagnie pour créer un produit.", $content['message']);
    }

    public function testWithMissingMandatoryPayload()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser("john@doe.com");
        $response = $this->postRequest(
            '/api/products',
            []
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("Le champ référence est manquant.", $content['ref'][0]);
        $this->assertEquals("Le champ description est manquant.", $content['description'][0]);
        $this->assertEquals("Le champ prix unitaire est manquant.", $content['unitPrice'][0]);
    }

    public function testWithInvalidUnitPrice()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser("john@doe.com");
        $response = $this->postRequest(
            '/api/products',
            [
                'ref' => 'Ref 1',
                'description' => 'Description 1',
                'unitPrice' => -10
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals("Le prix unitaire d'un produit doit être supérieur à 0€.", $content['unitPrice'][0]);
    }

    public function testSuccessfulCreateProduct()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser("john@doe.com");
        $response = $this->postRequest(
            '/api/products',
            [
                'ref' => 'Ref 1',
                'description' => 'Description 1',
                'unitPrice' => 10.2
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $content);
    }
}
