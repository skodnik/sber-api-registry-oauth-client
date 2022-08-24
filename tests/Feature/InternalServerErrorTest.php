<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Model\BadRequest;
use Vlsv\SberApiRegistryOauthClient\Model\InternalServerError;
use Vlsv\SberApiRegistryOauthClient\Model\Unauthorized;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class InternalServerErrorTest extends TestCaseBase
{
    public function test_deserializeSerialize(): array
    {
        $json = file_get_contents(__DIR__ . '/../samples/InternalServerError.json');

        /** @var InternalServerError $internalServerError */
        $internalServerError = $this->serializer->deserialize($json, InternalServerError::class, 'json');

        self::assertInstanceOf(InternalServerError::class, $internalServerError);
        self::assertJsonStringEqualsJsonString($json, $this->serializer->serialize($internalServerError, 'json'));

        return [json_decode($json), $internalServerError];
    }

    /** @depends test_deserializeSerialize */
    public function test_getters(array $array)
    {
        list($object, $internalServerError) = $array;

        self::assertEquals($object->moreInformation, $internalServerError->getMoreInformation());
        self::assertEquals($object->httpCode, $internalServerError->getHttpCode());
        self::assertEquals($object->httpMessage, $internalServerError->getHttpMessage());
    }
}
