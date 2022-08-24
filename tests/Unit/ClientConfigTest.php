<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Unit;

use Vlsv\SberApiRegistryOauthClient\ClientConfig;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class ClientConfigTest extends TestCaseBase
{
    public function test_Instance()
    {
        $clientId = 'f8c3dd11-3ba7-4aa1-ae77-df753e365fb7';
        $clientSecret = 'G6xF3eS0eU4iX5tP8nD7hS6kE1jD3sL5qP0dO5tC0jM0jQ7kY1';
        $host = 'https://api.sberbank.ru:8443/prod/tokens/v2';
        $authorizationString = 'ZjhjM2RkMTEtM2JhNy00YWExLWFlNzctZGY3NTNlMzY1ZmI3Okc2eEYzZVMwZVU0aVg1dFA4bkQ3aFM2a0UxakQzc0w1cVAwZE81dEMwak0walE3a1kx';

        $config = new ClientConfig(
            clientId: $clientId,
            clientSecret: $clientSecret,
            certPath: '/test/path',
            certPassword: 'test_password'
        );

        self::assertInstanceOf(ClientConfig::class, $config);

        self::assertEquals($clientId, $config->getClientId());
        self::assertEquals($clientSecret, $config->getClientSecret());
        self::assertEquals($host, $config->getHost());

        self::assertEquals(
            $authorizationString,
            $config->getBasicAuthorizationString()
        );
    }
}
