<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface EntityInterface
{

    public function getIdentifier():string;

    public function setIdentifier(string $identifier):void;
}