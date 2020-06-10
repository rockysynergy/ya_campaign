<?php
namespace Orqlog\Yacampaign\Domain\Service;

use Orqlog\Yacampaign\Domain\Model\CampaignInterface;

class CampaignService  implements CampaignServiceInterface
{

    /**
     * @var \Orqlog\Yacampaign\Domain\Model\CampaignInterface
     */
    protected $campaign;

    /**
     * Factory method to make Campaign
     */
    public function __construct(CampaignInterface $campaign)
    {
        $this->campaign = $campaign;
    }

    public function setCampaign(CampaignInterface $campaign)
    {
        $this->campaign = $campaign;
    }

    public function getCampaign() :CampaignInterface
    {
        return $this->campaign;
    }
    
}