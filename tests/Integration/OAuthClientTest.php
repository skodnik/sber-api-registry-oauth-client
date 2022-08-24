<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Integration;

use PHPUnit\Framework\TestCase;
use Vlsv\SberApiRegistryOauthClient\ClientConfig;
use Vlsv\SberApiRegistryOauthClient\Model\OAuthTokenResponse;
use Vlsv\SberApiRegistryOauthClient\OAuthClient;

class OAuthClientTest extends TestCase
{
    public function test_getOauthToken()
    {
        $config = new ClientConfig(
            clientId: getenv('CLIENT_ID'),
            clientSecret: getenv('CLIENT_SECRET'),
            certPath: getenv('CERT_PATH'),
            certPassword: getenv('CERT_PASSWORD'),
        );

        $oAuthClient = new OAuthClient($config);

        $oAuthTokenResponse = $oAuthClient->getOauthToken(scope: 'order.create');

        self::assertInstanceOf(OAuthTokenResponse::class, $oAuthTokenResponse);
    }
}
