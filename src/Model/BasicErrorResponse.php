<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Model;

abstract class BasicErrorResponse
{
    private string $moreInformation;

    private int $httpCode;

    private string $httpMessage;

    public function getMoreInformation(): string
    {
        return $this->moreInformation;
    }

    public function setMoreInformation(string $moreInformation): static
    {
        $this->moreInformation = $moreInformation;

        return $this;
    }

    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    public function setHttpCode(int $httpCode): static
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function getHttpMessage(): string
    {
        return $this->httpMessage;
    }

    public function setHttpMessage(string $httpMessage): static
    {
        $this->httpMessage = $httpMessage;

        return $this;
    }
}
