<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Exception;
use Orqlog\Yacampaign\Domain\Exception\IllegalArgumentException;
use Orqlog\Yacampaign\Domain\Exception\YacampaignException;
use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\AddressInterface;
use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use Orqlog\Yacampaign\Domain\Model\JoinRecordInterface;
use Orqlog\Yacampaign\Service\RegistryService;
use Orqlog\Yacampaign\Service\RegistryServiceInterface;

abstract class AbstractUser extends AbstractEntity implements UserInterface
{

    /**
     * @var array
     */
    protected $addresses = [];

    /**
     * @var AddressInterface
     */
    protected $defaultAddress = null;

    /**
     * @var array
     */
    protected $joinRecords = [];

    /**
     * @var RegistryServiceInterface
     */
    protected $registryService = null;

    public function __construct()
    {
        $this->registryService = new RegistryService();
    }


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

        if (!is_null($this->defaultAddress) && $this->defaultAddress->getIdentifier() == $identifier) {
            $this->defaultAddress = null;
        }
    }
    
    public function getDefaultAddress():?AddressInterface
    {
        return $this->defaultAddress;
    }

    public function getJoinRecords():array
    {
        return $this->joinRecords;
    }

    /**
     * Used to reconstitute the User object 
     */
    public function setJoinRecords(array $jRecords) :void 
    {
        $this->joinRecords = $jRecords;
    }

    public function joinCampaign(CampaignInterface $campaign):JoinRecordInterface
    {
        $this->canJoin($campaign);

        $joinRecord = $this->registryService->get(JoinRecordInterface::class);
        $joinRecord->setCampaign($campaign);
        $joinRecord->setJoinTime(new \DateTime());
        $joinRecord->setPrizes($campaign->decidePrize($this));

        array_push($this->joinRecords, $joinRecord);
        return $joinRecord;
    }

    protected function canJoin(CampaignInterface $campaign) :bool
    {
        if (!$campaign->isOpen()) {
            throw new YacampaignException('The campaign is closed!', 1590541214);
        }
        
        if (!$campaign->isQualified($this)) {
            throw new YacampaignException('You are not qualified to join this campaign!', 1590541309);
        }

        if (is_null($this->registryService)) {
            throw new IllegalArgumentException('Please provide the registry service!', 1590543073);
        }

        return true;
    }

    public function setRegistryService(RegistryServiceInterface $registryService) :void 
    {
        $this->registryService = $registryService;
    }

}