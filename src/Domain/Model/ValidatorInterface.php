<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface ValidatorInterface extends EntityInterface
{
    public function validate(Object $obj);
}