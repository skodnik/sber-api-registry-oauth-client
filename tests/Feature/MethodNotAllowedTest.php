<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Exception\ApiException;
use Vlsv\SberApiRegistryOauthClient\Model\MethodNotAllowed;
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
    public function test_getters(array $array): MethodNotAllowed
    {
        [$object, $methodNotAllowed] = $array;

        self::assertEquals($object->moreInformation, $methodNotAllowed->getMoreInformation());
        self::assertEquals($object->httpCode, $methodNotAllowed->getHttpCode());
        self::assertEquals($object->httpMessage, $methodNotAllowed->getHttpMessage());

        return $methodNotAllowed;
    }

    /**
     * @depends test_getters
     */
    public static function test_ApiExceptionWithMethodNotAllowed(MethodNotAllowed $methodNotAllowed): void
    {
        $exception = (new ApiException('Method Not Allowed', 405))
            ->setResponseObject($methodNotAllowed);

        self::assertInstanceOf(ApiException::class, $exception);
        self::assertInstanceOf(MethodNotAllowed::class, $exception->getResponseObject());
        self::assertEquals('Method Not Allowed', $exception->getMessage());
        self::assertEquals(405, $exception->getCode());
    }
}
