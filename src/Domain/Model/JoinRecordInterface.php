<?php

namespace Orqlog\Yacampaign\Domain\Model;

use Orqlog\Yacampaign\Domain\Model\Impl\JoinResult;

interface JoinRecordInterface extends EntityInterface
{

    public function getCampaign() :CampaignInterface;
    public function setCampaign(CampaignInterface $campaign) :void;

    public function getJoinTime(): \DateTime;
    public function setJoinTime(\DateTime $time):void;

    public function setPrizes(array $prizes):void;
    public function getPrizes():array;

}