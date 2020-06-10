<?php

namespace Orqlog\Yacampaign\Domain\Service;

use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\PrizeInterface;
use Orqlog\Yacampaign\Domain\Service\UserServiceInterface;
use Orqlog\Yacampaign\Domain\Model\QualificationPolicyInterface;

interface CampaignServiceInterface 
{
    
    /**
     * Determeine whether the use is qualified to join
     */
    public function isQualified(UserServiceInterface $userService):bool;

    /**
     * Get the prizes associated with this campain
     */
    public function getPrizes() :array;

    public function addPrize(PrizeInterface $prize);
    
    public function removePrize(PrizeInterface $prize);

    /**
     * Add qualificationPolicies
     */
    public function addQualificationPolicy(QualificationPolicyInterface $qPolicy) :void;

    /**
     * remove qualificationPolicy
     */
    public function removeQualificationPolicy(QualificationPolicyInterface $qPolicy) :void;

    public function decidePrize(UserInterface $user) :array;
}