<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use Orqlog\Yacampaign\Domain\Model\JoinRecordInterface;

class BaseJoinRecord extends AbstractEntity implements JoinRecordInterface
{
    /**
     * @var CampaignInterface
     */
    protected $campaign;

    /**
     * @var \DateTime
     */
    protected $joinTime;

    /**
     * @var array
     */
    protected $prizes = [];

    public function getCampaign() :CampaignInterface
    {
        return $this->campaign;
    }

    public function setCampaign(CampaignInterface $campaign): void
    {
        $this->campaign = $campaign;
    }

    public function setJoinTime(\DateTime $time): void
    {
        $this->joinTime = $time;
    }

    public function getJoinTime(): \DateTime
    {
        return $this->joinTime;
    }


    public function setPrizes($prizes): void
    {
        $this->prizes = $prizes;
    }

    public function getPrizes(): array
    {
        return $this->prizes;
    }
}