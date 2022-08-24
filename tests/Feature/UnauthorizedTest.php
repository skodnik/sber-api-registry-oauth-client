<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Model\BadRequest;
use Vlsv\SberApiRegistryOauthClient\Model\Unauthorized;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class UnauthorizedTest extends TestCaseBase
{
    public function test_deserializeSerialize(): array
    {
        $json = file_get_contents(__DIR__ . '/../samples/Unauthorized.json');

        /** @var Unauthorized $unauthorized */
        $unauthorized = $this->serializer->deserialize($json, Unauthorized::class, 'json');

        self::assertInstanceOf(Unauthorized::class, $unauthorized);
        self::assertJsonStringEqualsJsonString($json, $this->serializer->serialize($unauthorized, 'json'));

        return [json_decode($json), $unauthorized];
    }

    /** @depends test_deserializeSerialize */
    public function test_getters(array $array)
    {
        list($object, $unauthorized) = $array;

        self::assertEquals($object->moreInformation, $unauthorized->getMoreInformation());
        self::assertEquals($object->httpCode, $unauthorized->getHttpCode());
        self::assertEquals($object->httpMessage, $unauthorized->getHttpMessage());
    }
}
