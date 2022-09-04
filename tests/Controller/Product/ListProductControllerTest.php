<?php

namespace MyCompany\Tests\Controller\Product;

use MyCompany\Tests\AbstractWebtestCase;

class ListProductControllerTest extends AbstractWebtestCase
{
    public function testWithUnauthorizedUser()
    {
        $response = $this->getRequest('/api/products',);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testWithCompanyHasNoProduct()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml', AbstractWebtestCase::FIXTURES_FOLDER.'products.yml']);
        $this->logUser('foo@bar.com');

        $response = $this->getRequest('/api/products');
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals([], $content);
    }

    public function testListProductsToTakeFirstPage()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml', AbstractWebtestCase::FIXTURES_FOLDER.'products.yml']);
        $this->logUser('john@doe.com');

        $response = $this->getRequest('/api/products');
        $content = $this->getContentResponse($response->getContent());
        $headers = $response->headers;
        $this->assertCount(15, $content);
        $this->assertEquals(1, (int) $headers->get('pagination-page'));
        $this->assertEquals(7, (int) $headers->get('pagination-count'));
        $this->assertEquals(15, (int) $headers->get('pagination-limit'));
        $this->assertEquals(100, (int) $headers->get('element-total'));
        $this->assertTrue((bool) $headers->get('pagination-has-next'));
        $this->assertFalse((bool) $headers->get('pagination-has-previous'));
    }

    public function testWithOtherLimit()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml', AbstractWebtestCase::FIXTURES_FOLDER.'products.yml']);
        $this->logUser('john@doe.com');

        $response = $this->getRequest('/api/products?limit=20');
        $content = $this->getContentResponse($response->getContent());
        $headers = $response->headers;
        $this->assertCount(20, $content);
        $this->assertEquals(1, (int) $headers->get('pagination-page'));
        $this->assertEquals(5, (int) $headers->get('pagination-count'));
        $this->assertEquals(20, (int) $headers->get('pagination-limit'));
        $this->assertEquals(100, (int) $headers->get('element-total'));
        $this->assertTrue((bool) $headers->get('pagination-has-next'));
        $this->assertFalse((bool) $headers->get('pagination-has-previous'));
    }

    public function testWithLastPage()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml', AbstractWebtestCase::FIXTURES_FOLDER.'products.yml']);
        $this->logUser('john@doe.com');

        $response = $this->getRequest('/api/products?limit=20&page=5');
        $content = $this->getContentResponse($response->getContent());
        $headers = $response->headers;
        $this->assertCount(20, $content);
        $this->assertEquals(5, (int) $headers->get('pagination-page'));
        $this->assertEquals(5, (int) $headers->get('pagination-count'));
        $this->assertEquals(20, (int) $headers->get('pagination-limit'));
        $this->assertEquals(100, (int) $headers->get('element-total'));
        $this->assertFalse((bool) $headers->get('pagination-has-next'));
        $this->assertTrue((bool) $headers->get('pagination-has-previous'));
    }

    public function testWithSortParamAndFilters()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml', AbstractWebtestCase::FIXTURES_FOLDER.'products.yml']);
        $this->logUser('john@doe.com');

        $response = $this->getRequest('/api/products?limit=20&page=5&sort=unitPrice');
        $content = $this->getContentResponse($response->getContent());
        $headers = $response->headers;
        $this->assertCount(20, $content);
        $this->assertEquals(5, (int) $headers->get('pagination-page'));
        $this->assertEquals(5, (int) $headers->get('pagination-count'));
        $this->assertEquals(20, (int) $headers->get('pagination-limit'));
        $this->assertEquals(100, (int) $headers->get('element-total'));
        $this->assertFalse((bool) $headers->get('pagination-has-next'));
        $this->assertTrue((bool) $headers->get('pagination-has-previous'));
    }
}
