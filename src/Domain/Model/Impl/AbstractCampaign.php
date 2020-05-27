<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Exception\IllegalArgumentException;
use Orqlog\Yacampaign\Domain\Exception\NoQualificationPolicyFoundException;
use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\PrizeInterface;
use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use Orqlog\Yacampaign\Domain\Model\QualificationPolicyInterface;

abstract class AbstractCampaign extends AbstractEntity implements CampaignInterface
{
    /**
     * @var \DateTime
     */
    private $startAt;

    /**
     * @var \DateTime
     */
    private $expireAt;

    /**
     * @var array
     */
    private $qualificationPolicies = [];

    /**
     * @var
     */
    private $prizes = [];

    /**
     * @param \DateTime $startAt
     * @return void
     */
    public function setStartAt(\DateTime $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return \DateTime
     */
    public function getStartAt(): \DateTime
    {
        return $this->startAt;
    }

    /**
     * @param \DateTime $expireAt
     * @return void
     */
    public function setExpireAt(\DateTime $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    /**
     * @return \DateTime
     */
    public function getExpireAt(): \DateTime
    {
        return $this->expireAt;
    }

    /**
     * Whether the campain is still open
     * 
     * @return boolean
     */
    public function isOpen(): bool
    {
        $now = new \DateTime();
        return $now > $this->startAt && $now < $this->expireAt;
    }

    /**
     * Get the prizes associated with this campain
     * 
     * @return array 
     */
    public function getPrizes(): array
    {
        return $this->prizes;
    }

    /**
     * set prizes
     * 
     * @param array $prizes
     * @return void
     */
    public function setPrizes(array $prizes):void 
    {
        foreach ($prizes as $prize) {
            if (! $prize instanceof PrizeInterface) {
                throw new IllegalArgumentException('请提供实现了ProuctInterface接口的对象！');
            }

            $identifier = $prize->getIdentifier();
            $this->prizes[$identifier] = $prize;
        }
    }

    /**
     * add prize
     * 
     * @param PrizeInterface $prize
     * @return void
     */
    public function addPrize(PrizeInterface $prize):void
    {
        $identifier = $prize->getIdentifier();
        $this->prizes[$identifier] = $prize;
    }

    /**
     * Remove the prize
     * 
     * @param PrizeInterface $prize
     * @return void
     */
    public function removePrize(PrizeInterface $prize):void
    {
        $newProds= [];
        foreach ($this->prizes as $aProd) {
            if ($aProd->getIdentifier() !== $prize->getIdentifier()) {
                array_push($newProds, $aProd);
            }
        }

        $this->prizes = $newProds;
    }

    /**
     * Add qualificationPolicies, if it will overwrite the eixsting one based on Policy's identifier
     * 
     * @param QualificationPolicyInterface $qPolicy
     * @return void
     */
    public function addQualificationPolicy(QualificationPolicyInterface $qPolicy): void
    {
        $identifier = $qPolicy->getIdentifier();
        $this->qualificationPolicies[$identifier] = $qPolicy;
    }

    
    /**
     * set policies
     * 
     * @param array $policies
     * @return void
     */
    public function setQualificationPolicies(array $policies):void 
    {
        foreach ($policies as $policy) {
            if (! $policy instanceof QualificationPolicyInterface) {
                throw new IllegalArgumentException('请提供实现了QualificationPolicyInterface接口的对象！');
            }

            $identifier = $policy->getIdentifier();
            $this->qualificationPolicies[$identifier] = $policy;
        }
    }

    /**
     * Get qualification policies
     * 
     * @return array
     */
    public function getQualificationPolicies():array 
    {
        return $this->qualificationPolicies;
    }

    /**
     * remove qualificationPolicy
     */
    public function removeQualificationPolicy(QualificationPolicyInterface $qPolicy): void
    {
        $identifier = $qPolicy->getIdentifier();
        if (isset($this->qualificationPolicies[$identifier])) {
            unset($this->qualificationPolicies[$identifier]);
        }
    }

    /**
     * @param (QualificationPolicyInterface $qPolicy
     * @return boolean
     */
    public function hasQualificationPolicy(QualificationPolicyInterface $qPolicy): bool
    {
        $identifier = $qPolicy->getIdentifier();
        return isset($this->qualificationPolicies[$identifier]);
    }

    /**
     * Determeine whether the use is qualified to join
     */
    public function isQualified(UserInterface $user): bool
    {
        if (count($this->qualificationPolicies) < 1) {
            throw new NoQualificationPolicyFoundException('No policy!', 1590399166);
        }

        $result = true;
        foreach ($this->qualificationPolicies as $qPolicy) {
            if ($qPolicy->isQualified($user) === false) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}
