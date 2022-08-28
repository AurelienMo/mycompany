<?php

namespace MyCompany\Tests\Controller\Company;

use MyCompany\Tests\AbstractWebtestCase;

class GetCompanyTest extends AbstractWebtestCase
{
    public function testWithUnauthorizedUser()
    {
        $response = $this->getRequest('/api/companies');
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testWithUserHaventCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $this->logUser('john@doe.com');
        $response = $this->getRequest('/api/companies');
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('Aucune compagnie n\'a été trouvée.', $content['message']);
    }

    public function testSuccessGetCompany()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user_with_company.yml']);
        $this->logUser('john@doe.com');
        $response = $this->getRequest('/api/companies');
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Une compagnie', $content['companyName']);
        $this->assertTrue($content['isFreelance']);
        $this->assertEquals("street number", $content['streetNumber']);
        $this->assertEquals("streetname", $content['streetName']);
        $this->assertEquals("13010", $content['zipCode']);
        $this->assertEquals("Marseille", $content['city']);
    }
}
