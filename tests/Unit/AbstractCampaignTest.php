<?php

use PHPUnit\Framework\TestCase;
use Orqlog\Yacampaign\Domain\Model\Impl\AbstractCampaign;

final class AbstractCampaignTest extends TestCase
{

    /**
     * @test
     */
    public function isOpenReturnsTrueIfNowIsWithinCampainDates()
    {
        $campaign = new DummyCampaign();
        $campaign->setStartAt(new DateTime(date('Y-m-d', strtotime('-3 day'))));
        $campaign->setExpireAt(new DateTime(date('Y-m-d', strtotime('+3 day'))));

        $this->assertTrue($campaign->isOpen());
    }

    /**
     * @test
     */
    public function isOpenReturnsFalseIfCampainIsNotStartedYet()
    {
        $campaign = new DummyCampaign();
        $campaign->setStartAt(new DateTime(date('Y-m-d', strtotime('+3 day'))));
        $campaign->setExpireAt(new DateTime(date('Y-m-d', strtotime('+3 week'))));

        $this->assertFalse($campaign->isOpen());
    }

    /**
     * @test
     */
    public function isOpenReturnsFalseIfCampainIsExpired()
    {
        $campaign = new DummyCampaign();
        $campaign->setStartAt(new DateTime(date('Y-m-d', strtotime('-3 day'))));
        $campaign->setExpireAt(new DateTime(date('Y-m-d', strtotime('-3 minute'))));

        $this->assertFalse($campaign->isOpen());
    }

}

class DummyCampaign extends AbstractCampaign 
{
    public function decidePrize(\Orqlog\Yacampaign\Domain\Model\UserInterface $user) :array
    {
        return [['discount' => 0.8]];
    }
}