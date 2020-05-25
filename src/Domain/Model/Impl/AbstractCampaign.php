<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Exception\NoQualificationPolicyFoundException;
use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\ProductInterface;
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
     * Get the products associated with this campain
     */
    public function getProducts(): array
    {
        return [];
    }

    public function addProduct(ProductInterface $product)
    {
    }

    public function removeProduct(ProductInterface $product)
    {
    }

    /**
     * Add qualificationPolicies
     * 
     * @param QualificationPolicyInterface $qPolicy
     * @return void
     */
    public function addQualificationPolicy(QualificationPolicyInterface $qPolicy): void
    {
        array_push($this->qualificationPolicies, $qPolicy);
    }

    /**
     * remove qualificationPolicy
     */
    public function removeQualificationPolicy(QualificationPolicyInterface $qPolicy): void
    {
    }

    public function hasQualificationPolicy(QualificationPolicyInterface $qPolicy): bool
    {
        $result = false;
        foreach ($this->qualificationPolicies as $tqPolicy) {
            if ($tqPolicy->getIdentifier() === $qPolicy->getIdentifier()) {
                $result = true;
                break;
            }
        }

        return $result;
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
