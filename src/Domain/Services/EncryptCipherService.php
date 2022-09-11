<?php

namespace MyCompany\Domain\Services;

class EncryptCipherService
{
    private string $method = "aes-256-cbc";

    public function __construct(private string $cipherSecret) {}

    public function encrypt(string $data): string
    {
        $ivLen     = openssl_cipher_iv_length($this->method);
        $iv        = openssl_random_pseudo_bytes($ivLen);
        $encrypted = openssl_encrypt($data, $this->method, $this->cipherSecret, OPENSSL_RAW_DATA, $iv);

        return base64_encode($iv . $encrypted);
    }

    public function decrypt(string $data): string
    {
        $ivLen = openssl_cipher_iv_length($this->method);

        $data          = base64_decode($data);
        $iv            = substr($data, 0, $ivLen);
        $encryptedData = substr($data, $ivLen);

        return openssl_decrypt($encryptedData, $this->method, $this->cipherSecret, OPENSSL_RAW_DATA, $iv);
    }
}
