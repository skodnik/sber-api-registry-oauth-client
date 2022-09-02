<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Model\OAuthTokenResponse;
use Vlsv\SberApiRegistryOauthClient\Model\OidcTokenResponse;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class OidcTokenResponseTest extends TestCaseBase
{
    public function test_deserializeSerialize(): array
    {
        $json = file_get_contents(__DIR__ . '/../samples/OidcTokenResponse.json');

        /** @var OidcTokenResponse $oidcTokenResponse */
        $oidcTokenResponse = $this->serializer->deserialize($json, OidcTokenResponse::class, 'json');

        self::assertInstanceOf(OidcTokenResponse::class, $oidcTokenResponse);
        self::assertJsonStringEqualsJsonString($json, $this->serializer->serialize($oidcTokenResponse, 'json'));

        return [json_decode($json), $oidcTokenResponse];
    }

    /** @depends test_deserializeSerialize */
    public function test_getters(array $array)
    {
        list($object, $oidcTokenResponse) = $array;

        self::assertEquals($object->access_token, $oidcTokenResponse->getAccessToken());
        self::assertEquals($object->expires_in, $oidcTokenResponse->getExpiresIn());
        self::assertEquals($object->scope, $oidcTokenResponse->getScope());
        self::assertEquals($object->session_state, $oidcTokenResponse->getSessionState());
        self::assertEquals($object->token_type, $oidcTokenResponse->getTokenType());
        self::assertEquals($object->id_token, $oidcTokenResponse->getIdToken());
    }
}
