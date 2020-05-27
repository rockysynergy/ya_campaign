<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use Orqlog\Yacampaign\Domain\Model\JoinRecordInterface;

abstract class AbstractJoinRecord extends AbstractEntity implements JoinRecordInterface
{
    /**
     * @var CampaignInterface
     */
    protected $campaign;

    public function getCampaign() :CampaignInterface
    {
        return $this->campaign;
    }

    public function getJoinTime(): \DateTime
    {
        return $this->joinTime;
    }

}