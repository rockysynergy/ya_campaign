<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface JoinRecordInterface extends EntityInterface
{

    public function getCampaign() :CampaignInterface;

    public function getJoinTime(): \DateTime;
}