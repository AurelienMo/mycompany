<?php

namespace MyCompany\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Nelmio\Alice\Loader\NativeLoader;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractWebtestCase extends WebTestCase
{
    public const FIXTURES_FOLDER = __DIR__.'/fixtures/';

    protected EntityManagerInterface $entityManager;

    protected $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $container = static::getContainer();
        $this->entityManager = $container->get(EntityManagerInterface::class);
    }

    protected function getRequest(string $uri): Response
    {
        $this->client->request(
            'GET',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ]
        );

        return $this->client->getResponse();
    }

    protected function postRequest(string $uri, array $data): Response
    {
        $this->client->request(
            'POST',
            $uri,
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json'
            ],
            json_encode($data)
        );

        return $this->client->getResponse();
    }

    protected function loadFixtures(array $paths): void
    {
        $loader = new NativeLoader();
        $objectSet = $loader->loadFiles($paths);

        foreach ($objectSet->getObjects() as $object) {
            $this->entityManager->persist($object);
        }
        $this->entityManager->flush();
    }

    protected function getContentResponse(string $responseBody): array
    {
        return json_decode($responseBody, true);
    }
}
