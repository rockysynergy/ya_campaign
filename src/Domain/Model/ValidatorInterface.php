<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface ValidatorInterface extends EntityInterface
{
    public function isValid(Object $obj):bool;
}