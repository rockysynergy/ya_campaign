<?php

use PHPUnit\Framework\TestCase;
use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\Impl\AbstractCampaign;
use Orqlog\Yacampaign\Domain\Model\QualificationPolicyInterface;
use Orqlog\Yacampaign\Domain\Model\Impl\AbstractQualificationPolicy;
use Orqlog\Yacampaign\Domain\Exception\NoQualificationPolicyFoundException;

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

    /**
     * @test
     */
    public function hasQualificationPolicyReturnsTrueIfCampainDoesHaveThePolicy()
    {
        $campaign = new DummyCampaign();
        $mUser = $this->createMock(UserInterface::class);
        $mqPolicy = new DummyQualificationPolicy();
        $mqPolicy->setIdentifier('ThePolicy_1');
        $campaign->addQualificationPolicy($mqPolicy);

        $mq2Policy = new DummyQualificationPolicy();
        $mq2Policy->setIdentifier('ThePolicy_1');

        $this->assertTrue($campaign->hasQualificationPolicy($mq2Policy));
    }

    /**
     * @test
     */
    public function isQualifiedThrowsExceptionIfNoQualificationPolicyIsAdded()
    {
        $this->expectException(NoQualificationPolicyFoundException::class);
        $this->expectExceptionCode(1590399166);
        $campaign = new DummyCampaign();
        $mUser = $this->createMock(UserInterface::class);

        $campaign->isQualified($mUser);
    }

    /**
     * @test
     */
    public function isQualifiedReturnsTrueIfQualificationPolicyResolveToTrueForUser()
    {
        $campaign = new DummyCampaign();
        $mUser = $this->createMock(UserInterface::class);
        $mqPolicy = $this->createMock(QualificationPolicyInterface::class);
        $mqPolicy->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($mUser))
            ->willReturn(true);

        $campaign->addQualificationPolicy($mqPolicy);

        $this->assertTrue($campaign->isQualified($mUser));
    }

    /**
     * @test
     */
    public function isQualifiedReturnsFalseIfOneQualificationPolicyResolveToFalseForUser()
    {
        $campaign = new DummyCampaign();
        $mUser = $this->createMock(UserInterface::class);

        $mq2Policy = $this->createMock(QualificationPolicyInterface::class);
        $mq2Policy->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($mUser))
            ->willReturn(true);
        $campaign->addQualificationPolicy($mq2Policy);

        $mqPolicy = $this->createMock(QualificationPolicyInterface::class);
        $mqPolicy->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($mUser))
            ->willReturn(false);
        $campaign->addQualificationPolicy($mqPolicy);

        $this->assertFalse($campaign->isQualified($mUser));
    }
}

class DummyCampaign extends AbstractCampaign 
{

}


class DummyQualificationPolicy extends AbstractQualificationPolicy {

}