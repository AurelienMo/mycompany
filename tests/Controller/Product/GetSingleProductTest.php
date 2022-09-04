<?php

namespace MyCompany\Tests\Controller\Product;

use MyCompany\Domain\Entity\Company;
use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Tests\AbstractWebtestCase;

class GetSingleProductTest extends AbstractWebtestCase
{
    private const UUID_PRODUCT = 'c0a80163-7b1f-11e8-9c9c-2d42b21b1a3e';

    public function testWithUnauthorizedUser()
    {
        $response = $this->getRequest(
            sprintf('/api/products/%s', self::UUID_PRODUCT)
        );
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testGetOnNotFoundProduct()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser('john@doe.com');
        $response = $this->getRequest(sprintf('/api/products/%s', "c0a80163-7b1f-11e8-9c9c-2d42b21b1a3f"));
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Ce produit n'existe pas.", $content['message']);
    }

    public function testAccessOnProductFromOtherCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $commpanyFoobar = $this->entityManager->getRepository(UserAccount::class)->findOneBy(['email' => 'foo@bar.com'])->getCompany();
        $product = new Product(
            $commpanyFoobar,
            'Product 1',
            'Description 1',
            10
        );
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $this->logUser('john@doe.com');
        $response = $this->getRequest(sprintf('/api/products/%s', $product->getId()->toString()));
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals("Vous n'avez pas accès à ce produit.", $content['message']);
    }

    public function testSuccessfulGetProductInformation()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser('john@doe.com');
        $commpany = $this->entityManager->getRepository(UserAccount::class)->findOneBy(['email' => 'john@doe.com'])->getCompany();
        $product = new Product(
            $commpany,
            'Product 1',
            'Description 1',
            10.2
        );
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $response = $this->getRequest(sprintf('/api/products/%s', $product->getId()->toString()));
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($product->getId()->toString(), $content['id']);
        $this->assertEquals('Product 1', $content['ref']);
        $this->assertEquals('Description 1', $content['description']);
        $this->assertEquals(10.2, $content['unitPrice']);
    }
}
