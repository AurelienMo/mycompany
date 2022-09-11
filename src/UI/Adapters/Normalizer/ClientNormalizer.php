<?php

namespace MyCompany\UI\Adapters\Normalizer;

use MyCompany\Domain\Entity\Client;
use MyCompany\Domain\Services\EncryptCipherService;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ClientNormalizer implements NormalizerInterface
{
    public function __construct(private EncryptCipherService $cipherService) {}

    public function normalize(
        mixed $object,
        string $format = null,
        array $context = []
    ) {
        return [
            'id' => $object->getId()->toString(),
            'firstname' => $this->cipherService->decrypt($object->getFirstname()),
            'lastname' => $this->cipherService->decrypt($object->getLastname()),
            'email' => $this->cipherService->decrypt($object->getEmail()),
            'streetNumber' => $object->getStreetNumber() ? $this->cipherService->decrypt($object->getStreetNumber()) : null,
            'streetName' => $this->cipherService->decrypt($object->getStreetName()),
            'zipCode' => $this->cipherService->decrypt($object->getZipCode()),
            'city' => $this->cipherService->decrypt($object->getCity()),
        ];
    }

    public function supportsNormalization(mixed $data, string $format = null)
    {
        return $data instanceof Client;
    }
}
