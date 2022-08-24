<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Model;

class MethodNotAllowed extends BasicErrorResponse
{
    private int $httpCode;

    private string $httpMessage;

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $httpCode): MethodNotAllowed
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function getHttpMessage(): string
    {
        return $this->httpMessage;
    }

    public function setHttpMessage(string $httpMessage): MethodNotAllowed
    {
        $this->httpMessage = $httpMessage;

        return $this;
    }
}
