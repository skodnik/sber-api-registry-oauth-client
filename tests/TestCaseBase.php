<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;
use Vlsv\SberApiRegistryOauthClient\ClientConfig;
use Vlsv\SberApiRegistryOauthClient\Factory\SerializerFactory;
use Vlsv\SberApiRegistryOauthClient\OAuthClient;

class TestCaseBase extends TestCase
{
    protected Serializer $serializer;
    protected OAuthClient $apiClient;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->serializer = SerializerFactory::getSerializer();

        $clientConfig = new ClientConfig(
            clientId: getenv('CLIENT_ID'),
            clientSecret: getenv('CLIENT_SECRET'),
            certPath: getenv('CERT_PATH'),
            certPassword: getenv('CERT_PASSWORD'),
            host: getenv('HOST'),
        );
        $this->apiClient = new OAuthClient(config: $clientConfig);
    }
}
