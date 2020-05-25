<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Model\EntityInterface;

abstract class AbstractEntity implements EntityInterface
{
    private $identifier;

    public function setIdentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}