<?php

use Orqlog\Yacampaign\Domain\Model\AddressInterface;
use PHPUnit\Framework\TestCase;
use Orqlog\Yacampaign\Domain\Model\Impl\AbstractUser;

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
        $this->assertTrue(false);
    }
}

class DummyUser extends AbstractUser {
    
}