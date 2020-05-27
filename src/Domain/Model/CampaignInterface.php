<?php

namespace Orqlog\Yacampaign\Domain\Model;


interface CampaignInterface extends EntityInterface
{
    /**
     * Whether the campain is still open
     */
    public function isOpen() :bool;

    /**
     * Determeine whether the use is qualified to join
     */
    public function isQualified(UserInterface $user):bool;

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