<?php

namespace Orqlog\Yacampaign\Domain\Model;


interface CampaignInterface extends EntityInterface
{
    /**
     * Whether the campain is still open
     */
    public function isOpen() :bool;
}