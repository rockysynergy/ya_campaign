<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Exception\IllegalArgumentException;
use Orqlog\Yacampaign\Domain\Model\ValidatorInterface;

class CampaignValidator implements ValidatorInterface 
{
    public function validate(AbstractCampaign $campaign):void
    {
        if ($campaign->getExpireAt() <= $campaign->getStartAt()) {
            throw new IllegalArgumentException("Expire time can not be ahead of start time!", 1591674076);
        }
    }
}