<?php

namespace MyCompany\Tests\Controller\Product;

use MyCompany\Domain\Entity\Product;
use MyCompany\Domain\Entity\UserAccount;
use MyCompany\Tests\AbstractWebtestCase;

class DeleteProductTest extends AbstractWebtestCase
{
    public function testWithUnauthorizedUser()
    {
        $response = $this->delete(
            '/api/products/1',
        );
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testWithUserHaventCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER . 'user.yml']);
        $this->logUser("john@doe.com");
        $response = $this->delete('/api/products/1');
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals("Vous devez être associé à une compagnie pour supprimer un produit.", $content['message']);
    }

    public function testDeleteOnNotFoundProduct()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER . 'user_with_company.yml']);
        $this->logUser("john@doe.com");
        $response = $this->delete(sprintf("/api/products/%s", "c0a80163-7b1f-11e8-9c9c-2d42b21b1a3e"));
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals("Ce produit n'existe pas.", $content['message']);
    }

    public function testDeleteToAnotherCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER . 'user_with_company.yml']);
        $this->logUser("john@doe.com");
        $company = $this->entityManager->getRepository(UserAccount::class)->findOneBy(['email' => 'foo@bar.com'])->getCompany();
        $product = Product::create(
            "Ref 1",
            "Description 1",
            10,
            $company
        );
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $response = $this->delete(sprintf("/api/products/%s", $product->getId()->toString()));
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals("Vous ne pouvez pas supprimer un produit qui ne vous appartient pas.", $content['message']);
    }

    public function testSuccessfulDelete()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER . 'user_with_company.yml']);
        $this->logUser("john@doe.com");
        $company = $this->entityManager->getRepository(UserAccount::class)->findOneBy(['email' => 'john@doe.com'])->getCompany();
        $product = Product::create(
            "Ref 1",
            "Description 1",
            10,
            $company
        );
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        $productsInDatabase = $this->entityManager->getRepository(Product::class)->findBy(['company' => $company]);
        $this->assertCount(1, $productsInDatabase);
        $response = $this->delete(sprintf("/api/products/%s", $product->getId()->toString()));
        $this->assertEquals(204, $response->getStatusCode());
        $productsInDatabase = $this->entityManager->getRepository(Product::class)->findBy(['company' => $company]);
        $this->assertCount(0, $productsInDatabase);
    }
}
