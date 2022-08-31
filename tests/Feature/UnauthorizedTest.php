<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Exception\ApiException;
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
    public function test_getters(array $array): Unauthorized
    {
        [$object, $unauthorized] = $array;

        self::assertEquals($object->moreInformation, $unauthorized->getMoreInformation());
        self::assertEquals($object->httpCode, $unauthorized->getHttpCode());
        self::assertEquals($object->httpMessage, $unauthorized->getHttpMessage());

        return $unauthorized;
    }

    /**
     * @depends test_getters
     */
    public static function test_ApiExceptionWithUnauthorized(Unauthorized $unauthorized): void
    {
        $exception = (new ApiException('Client Error 401 Unauthorized', 401))
            ->setResponseObject($unauthorized);

        self::assertInstanceOf(ApiException::class, $exception);
        self::assertInstanceOf(Unauthorized::class, $exception->getResponseObject());
        self::assertEquals('Client Error 401 Unauthorized', $exception->getMessage());
        self::assertEquals(401, $exception->getCode());
    }
}
