<?php

namespace MyCompany\Tests\Controller\User;

use MyCompany\Tests\AbstractWebtestCase;

class AuthenticationTest extends AbstractWebtestCase
{
    public function testWithInvalidEmail()
    {
        $response = $this->postRequest(
            '/api/login_check',
            [
                'username' => 'john@doe.com',
                'password' => '12345678'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals("Identifiants invalides.", $content['message']);
    }

    public function testWithInvalidCredentials()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $response = $this->postRequest(
            '/api/login_check',
            [
                'username' => 'john@doe.com',
                'password' => '1234567'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(401, $response->getStatusCode());
        $this->assertEquals("Identifiants invalides.", $content['message']);
    }

    public function testSuccessLogin()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $response = $this->postRequest(
            '/api/login_check',
            [
                'username' => 'john@doe.com',
                'password' => '12345678'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertArrayHasKey('token', $content);
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('refresh_token', $content);
    }
}
