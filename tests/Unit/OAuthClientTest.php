<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Tests\Unit;

use Vlsv\SberApiRegistryOauthClient\Tests\TestCaseBase;

class OAuthClientTest extends TestCaseBase
{
    public function test_getRquid()
    {
        self::assertIsString($this->apiClient->getRqUID());
        self::assertEquals(32, strlen($this->apiClient->getRqUID()));
    }
}
