<?php

namespace Orqlog\Yacampaign\Domain\Service;

use Orqlog\Yacampaign\Domain\Model\CampaignInterface;

class CampaignService 
{
    /**
     * @var 
     */
    protected $campaignClass;

    /**
     * Factory method to make Campaign
     */
    public function createCampaign(\DateTime $startTime, \DateTime $endTime, array $products, array $policies) :CampaignInterface
    {
        $campaign = new $this->campaignClass();
        $campaign->setStartTime($startTime);
        $campaign->setEndTime($endTime);
        $campaign->setProducts($products);
        $campaign->setQualificationPolicies($policies);

        return $campaign;
    }
}