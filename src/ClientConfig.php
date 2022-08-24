<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient;

class ClientConfig
{
    public function __construct(
        protected string $clientId,
        protected string $clientSecret,
        protected string $certPath = '',
        protected string $certPassword = '',
        protected string $host = 'https://api.sberbank.ru:8443/prod/tokens/v2',
    ) {
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    public function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    public function getBasicAuthorizationString(): string
    {
        return base64_encode($this->getClientId() . ':' . $this->getClientSecret());
    }

    public function getCertPath(): string
    {
        return $this->certPath;
    }

    public function getCertPassword(): string
    {
        return $this->certPassword;
    }

    public function getHost(): string
    {
        return $this->host;
    }
}
