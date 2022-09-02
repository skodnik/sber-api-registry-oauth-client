<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Model\OAuthTokenResponse;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class OAuthTokenResponseTest extends TestCaseBase
{
    public function test_deserializeSerialize(): array
    {
        $json = file_get_contents(__DIR__ . '/../samples/OAuthTokenResponse.json');

        /** @var OAuthTokenResponse $oAuthTokenResponse */
        $oAuthTokenResponse = $this->serializer->deserialize($json, OAuthTokenResponse::class, 'json');

        self::assertInstanceOf(OAuthTokenResponse::class, $oAuthTokenResponse);
        self::assertJsonStringEqualsJsonString($json, $this->serializer->serialize($oAuthTokenResponse, 'json'));

        return [json_decode($json), $oAuthTokenResponse];
    }

    /** @depends test_deserializeSerialize */
    public function test_getters(array $array)
    {
        list($object, $oAuthTokenResponse) = $array;

        self::assertEquals($object->access_token, $oAuthTokenResponse->getAccessToken());
        self::assertEquals($object->expires_in, $oAuthTokenResponse->getExpiresIn());
        self::assertEquals($object->scope, $oAuthTokenResponse->getScope());
        self::assertEquals($object->session_state, $oAuthTokenResponse->getSessionState());
        self::assertEquals($object->token_type, $oAuthTokenResponse->getTokenType());
    }
}
