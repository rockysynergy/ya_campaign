<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Model\CampaignInterface;

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

}
