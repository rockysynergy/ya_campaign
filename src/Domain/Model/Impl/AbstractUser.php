<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\AddressInterface;
use Orqlog\Yacampaign\Domain\Model\CampaignInterface;

abstract class AbstractUser extends AbstractEntity implements UserInterface
{

    /**
     * @var
     */
    protected $addresses = [];

    /**
     * @var
     */
    protected $defaultAddress = null;


    public function getAddresses():array
    {
        return $this->addresses;
    }

    public function addAddress(AddressInterface $address):void 
    {
        $identifier = $address->getIdentifier();
        $this->addresses[$identifier] = $address;

        if ($address->isDefault()) {
            $this->defaultAddress = $address;
        }
    }

    public function removeAddress(AddressInterface $address):void
    {
        $identifier = $address->getIdentifier();
        if (isset($this->addresses[$identifier])) {
            unset($this->addresses[$identifier]);
        }
    }
    
    public function getDefaultAddress():?AddressInterface
    {
        return $this->defaultAddress;
    }

    public function getJoinRecords():array
    {
        return [];
    }

    public function joinCampaign(CampaignInterface $campaign):void
    {
        
    }

}