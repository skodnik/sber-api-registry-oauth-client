<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Vlsv\SberApiRegistryOauthClient\Exception\ApiException;
use Vlsv\SberApiRegistryOauthClient\Model\InternalServerError;
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
    public function test_getters(array $array): InternalServerError
    {
        [$object, $internalServerError] = $array;

        self::assertEquals($object->moreInformation, $internalServerError->getMoreInformation());
        self::assertEquals($object->httpCode, $internalServerError->getHttpCode());
        self::assertEquals($object->httpMessage, $internalServerError->getHttpMessage());

        return $internalServerError;
    }

    /**
     * @depends test_getters
     */
    public static function test_ApiExceptionWithInternalServerError(InternalServerError $internalServerError): void
    {
        $exception = (new ApiException('Server Error 500 Internal Server Error', 500))
            ->setResponseObject($internalServerError);

        self::assertInstanceOf(ApiException::class, $exception);
        self::assertInstanceOf(InternalServerError::class, $exception->getResponseObject());
        self::assertEquals('Server Error 500 Internal Server Error', $exception->getMessage());
        self::assertEquals(500, $exception->getCode());
    }
}
