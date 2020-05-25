<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Model\EntityInterface;

abstract class AbstractEntity implements EntityInterface
{
    private $identifier;

    public function setIentifier(string $identifier): void
    {
        $this->identifier = $identifier;
    }

    public function getIentifier(): string
    {
        return $this->identifier;
    }
}