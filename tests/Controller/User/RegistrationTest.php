<?php

namespace MyCompany\Tests\Controller\User;

use MyCompany\Entity\UserAccount;
use MyCompany\Tests\AbstractWebtestCase;

class RegistrationTest extends AbstractWebtestCase
{
    public function testOnInvalidPayload()
    {
        $response = $this->postRequest('/api/registration', []);
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());

        foreach ($content['email'] as $value) {
            $this->assertEquals("L'adresse email est requise.", $value);
        }
        foreach ($content['password'] as $value) {
            $this->assertEquals("Le mot de passe est requis.", $value);
        }
    }

    public function testOnAlreadyExistUser()
    {
        $this->loadFixtures([AbstractWebtestCase::FIXTURES_FOLDER.'user.yml']);
        $response = $this->postRequest(
            '/api/registration',
            [
                'email' => 'john@doe.com',
                'password' => '12345678'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($content['email'][0], "Compte utilisateur déjà existant.");
    }

    public function testOnInvalidPassword()
    {
        $response = $this->postRequest(
            '/api/registration',
            [
                'email' => 'john@doe.com',
                'password' => '1234567'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals($content['password'][0], "La longueur du mot de passe doit contenir au moins 8 caractères.");
    }

    public function testSuccessfullRegistration()
    {
        $response = $this->postRequest(
            '/api/registration',
            [
                'email' => 'john@doe.com',
                'password' => '12345678'
            ]
        );
        $content = $this->getContentResponse($response->getContent());
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $content);
        $this->assertArrayHasKey('token', $content);
        $this->assertArrayHasKey('refresh_token', $content);
        $user = $this->entityManager->getRepository(UserAccount::class)->findOneBy(['email' => 'john@doe.com']);
        $this->assertTrue($user instanceof UserAccount);
    }
}
