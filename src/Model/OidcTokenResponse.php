<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Model;

class OidcTokenResponse extends BasicTokenResponse
{
    private string $idToken;

    public function getIdToken(): string
    {
        return $this->idToken;
    }

    public function setIdToken(string $idToken): OidcTokenResponse
    {
        $this->idToken = $idToken;

        return $this;
    }
}
