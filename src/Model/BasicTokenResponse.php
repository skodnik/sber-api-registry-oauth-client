<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Model;

class BasicTokenResponse
{
    private string $accessToken;

    private int $expiresIn;

    private string $scope;

    private string $sessionState;

    private string $tokenType;

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function setAccessToken(string $accessToken): BasicTokenResponse
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function setExpiresIn(int $expiresIn): BasicTokenResponse
    {
        $this->expiresIn = $expiresIn;

        return $this;
    }

    public function getScope(): string
    {
        return $this->scope;
    }

    public function setScope(string $scope): BasicTokenResponse
    {
        $this->scope = $scope;

        return $this;
    }

    public function getSessionState(): string
    {
        return $this->sessionState;
    }

    public function setSessionState(string $sessionState): BasicTokenResponse
    {
        $this->sessionState = $sessionState;

        return $this;
    }

    public function getTokenType(): string
    {
        return $this->tokenType;
    }

    public function setTokenType(string $tokenType): BasicTokenResponse
    {
        $this->tokenType = $tokenType;

        return $this;
    }
}
