<?php

declare(strict_types=1);

namespace Vlsv\SberApiRegistryOauthClient\Model;

class BasicErrorResponse
{
    private string $moreInformation;

    public function getMoreInformation(): string
    {
        return $this->moreInformation;
    }

    public function setMoreInformation(string $moreInformation): BasicErrorResponse
    {
        $this->moreInformation = $moreInformation;

        return $this;
    }
}
