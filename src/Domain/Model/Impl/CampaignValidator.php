<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Exception\IllegalArgumentException;
use Orqlog\Yacampaign\Domain\Model\ValidatorInterface;

class CampaignValidator implements ValidatorInterface 
{
    public function isValid(AbstractCampaign $campaign)
    {
        if ($campaign->getExpireAt() <= $campaign->getStartAt()) {
            throw new IllegalArgumentException("Expire time can not be ahead of start time!", 1591674076);
        }
    }
}