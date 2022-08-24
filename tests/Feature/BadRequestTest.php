<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Model\BadRequest;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class BadRequestTest extends TestCaseBase
{
    public function test_deserializeSerialize(): array
    {
        $json = file_get_contents(__DIR__ . '/../samples/BadRequest.json');

        /** @var BadRequest $badRequest */
        $badRequest = $this->serializer->deserialize($json, BadRequest::class, 'json');

        self::assertInstanceOf(BadRequest::class, $badRequest);
        self::assertJsonStringEqualsJsonString($json, $this->serializer->serialize($badRequest, 'json'));

        return [json_decode($json), $badRequest];
    }

    /** @depends test_deserializeSerialize */
    public function test_getters(array $array)
    {
        list($object, $badRequest) = $array;

        self::assertEquals($object->moreInformation, $badRequest->getMoreInformation());
        self::assertEquals($object->httpCode, $badRequest->getHttpCode());
        self::assertEquals($object->httpMessage, $badRequest->getHttpMessage());
    }
}
