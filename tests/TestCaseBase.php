<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TestCaseBase extends TestCase
{
    protected Serializer $serializer;
    protected Serializer $serializerWithNameConverter;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->serializer = new Serializer(
            normalizers: [new ObjectNormalizer()],
            encoders: [new JsonEncoder()],
        );

        $this->serializerWithNameConverter = new Serializer(
            normalizers: [new ObjectNormalizer(
                classMetadataFactory: null,
                nameConverter: new CamelCaseToSnakeCaseNameConverter()
            )],
            encoders: [new JsonEncoder()],
        );
    }
}
