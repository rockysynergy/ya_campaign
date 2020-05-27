<?php

use Orqlog\Yacampaign\Domain\Exception\YacampaignException;
use Orqlog\Yacampaign\Domain\Model\AddressInterface;
use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use PHPUnit\Framework\TestCase;
use Orqlog\Yacampaign\Domain\Model\Impl\AbstractUser;
use Orqlog\Yacampaign\Domain\Model\JoinRecordInterface;
use Orqlog\Yacampaign\Service\RegistryServiceInterface;

final class AbstractUserTest extends TestCase
{
    /**
     * @test
     */
    public function testAddAddress()
    {
        $addressId = 'user_id';

        $user = new DummyUser();
        $address = $this->createMock(AddressInterface::class);
        $address->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($addressId);

        $user->addAddress($address);
        $addresses = $user->getAddresses();
        $this->assertEquals($addressId, $addresses[$addressId]->getIdentifier());
    }

    /**
     * @test
     */
    public function testAddAddressSetDefaultAddressIfPresent()
    {

        $user = new DummyUser();
        $addressId1 = 'user_id';
        $address1 = $this->createMock(AddressInterface::class);
        $address1->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($addressId1);
        $user->addAddress($address1);

        $addressId2 = 'address_2';
        $address2 = $this->createMock(AddressInterface::class);
        $address2->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($addressId2);
        $address2->expects($this->any())
            ->method('isDefault')
            ->willReturn(true);
        $user->addAddress($address2);


        $addresses = $user->getAddresses();
        $this->assertEquals($addressId1, $addresses[$addressId1]->getIdentifier());

        $this->assertEquals($addressId2, $user->getDefaultAddress()->getIdentifier());
    }

    /**
     * @test
     */
    public function removeAddress()
    {
        $user = new DummyUser();

        $addressId2 = 'address_2';
        $address2 = $this->createMock(AddressInterface::class);
        $address2->expects($this->any())
            ->method('getIdentifier')
            ->willReturn($addressId2);
        $address2->expects($this->any())
            ->method('isDefault')
            ->willReturn(true);
        $user->addAddress($address2);

        $user->removeAddress($address2);
        $addresses = $user->getAddresses();
        $this->assertEquals(0, count($addresses));

        $this->assertNull($user->getDefaultAddress());
    }

    /**
     * @test
     */
    public function joinCampaignThrowsExceptionIfTheCampaignIsNotOpen()
    {
        $this->expectException(YacampaignException::class);
        $this->expectExceptionCode(1590541214);

        $campaign = $this->createMock(CampaignInterface::class);
        $campaign->expects($this->once())
            ->method('isOpen')
            ->willReturn(false);

        $user = new DummyUser();
        $user->joinCampaign($campaign);
    }

    /**
     * @test
     */
    public function joinCampaignThrowsExceptionIfTheUserIsNotQualified()
    {
        $this->expectException(YacampaignException::class);
        $this->expectExceptionCode(1590541309);

        $user = new DummyUser();
        $campaign = $this->createMock(CampaignInterface::class);
        $campaign->expects($this->once())
            ->method('isOpen')
            ->willReturn(true);
        $campaign->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($user))
            ->willReturn(false);

        $user->joinCampaign($campaign);
    }

    /**
     * @test
     */
    public function joinCampaignSuccessWouldAddTheJoinRecords()
    {
        $user = new DummyUser();
        $campaign = $this->createMock(CampaignInterface::class);
        $campaign->expects($this->once())
            ->method('isOpen')
            ->willReturn(true);
        $campaign->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($user))
            ->willReturn(true);

        $joinRecords = $this->createMock(JoinRecordInterface::class);
        
        $registry = $this->createMock(RegistryServiceInterface::class);
        $registry->expects($this->once())
            ->method('get')
            ->with($this->equalTo(JoinRecordInterface::class))
            ->willReturn($joinRecords);
        $user->setRegistryService($registry);

        $user->joinCampaign($campaign);
        $joinRecords = $user->getJoinRecords();
        $this->assertEquals(1, count($joinRecords));
    }

    /**
     * @test
     */
    public function joinCampaignSuccessWouldAddTheJoinRecordProperties()
    {
        $user = new DummyUser();
        $campaign = $this->createMock(CampaignInterface::class);
        $campaign->expects($this->once())
            ->method('isOpen')
            ->willReturn(true);
        $campaign->expects($this->once())
            ->method('isQualified')
            ->with($this->equalTo($user))
            ->willReturn(true);

        $joinRecord = $this->createMock(JoinRecordInterface::class);
        $joinRecord->expects($this->once())
            ->method('setCampaign');
        $joinRecord->expects($this->once())
            ->method('setJoinTime');
        $joinRecord->expects($this->once())
            ->method('setPrizes');
        
        $registry = $this->createMock(RegistryServiceInterface::class);
        $registry->expects($this->once())
            ->method('get')
            ->with($this->equalTo(JoinRecordInterface::class))
            ->willReturn($joinRecord);
        $user->setRegistryService($registry);

        $user->joinCampaign($campaign);
        $joinRecords = $user->getJoinRecords();
        $this->assertEquals(1, count($joinRecords));
    }
}

class DummyUser extends AbstractUser
{
}
