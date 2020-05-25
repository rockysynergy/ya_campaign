<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface EntityInterface
{

    public function getIentifier():string;

    public function setIentifier(string $identifier):void;
}