<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Exception;

use Exception;
use Throwable;
use Vlsv\SberApiRegistryOauthClient\Model\BasicErrorResponse;

class ApiException extends Exception
{
    protected ?BasicErrorResponse $responseObject = null;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function setResponseObject(?BasicErrorResponse $object): static
    {
        $this->responseObject = $object;

        return $this;
    }

    public function getResponseObject(): ?BasicErrorResponse
    {
        return $this->responseObject;
    }
}
