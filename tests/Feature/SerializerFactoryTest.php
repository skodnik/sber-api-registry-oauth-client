<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Feature;

use Symfony\Component\Serializer\Serializer;
use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class SerializerFactoryTest extends TestCaseBase
{
    public function test_instance()
    {
        self::assertInstanceOf(Serializer::class, $this->serializer);
    }
}
