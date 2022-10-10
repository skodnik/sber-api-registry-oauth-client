<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Model;

use Symfony\Component\Serializer\Annotation\SerializedName;

abstract class BasicErrorResponse
{
    /** @SerializedName("moreInformation") */
    private string $moreInformation;

    /** @SerializedName("httpCode") */
    private string $httpCode;

    /** @SerializedName("httpMessage") */
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

    public function getHttpCode(): string
    {
        return $this->httpCode;
    }

    public function setHttpCode(string $httpCode): static
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
