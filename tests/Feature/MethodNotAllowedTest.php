<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Model\BadRequest;
use Vlsv\SberApiRegistryOauthClient\Model\MethodNotAllowed;
use Vlsv\SberApiRegistryOauthClient\Model\Unauthorized;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class MethodNotAllowedTest extends TestCaseBase
{
    public function test_deserializeSerialize(): array
    {
        $json = file_get_contents(__DIR__ . '/../samples/MethodNotAllowed.json');

        /** @var MethodNotAllowed $methodNotAllowed */
        $methodNotAllowed = $this->serializer->deserialize($json, MethodNotAllowed::class, 'json');

        self::assertInstanceOf(MethodNotAllowed::class, $methodNotAllowed);
        self::assertJsonStringEqualsJsonString($json, $this->serializer->serialize($methodNotAllowed, 'json'));

        return [json_decode($json), $methodNotAllowed];
    }

    /** @depends test_deserializeSerialize */
    public function test_getters(array $array)
    {
        list($object, $methodNotAllowed) = $array;

        self::assertEquals($object->moreInformation, $methodNotAllowed->getMoreInformation());
        self::assertEquals($object->httpCode, $methodNotAllowed->getHttpCode());
        self::assertEquals($object->httpMessage, $methodNotAllowed->getHttpMessage());
    }
}
