<?php

namespace Orqlog\Yacampaign\Domain\Service;

use CampaignServiceInterface;
use Orqlog\Yacampaign\Service\RegistryService;
use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\AddressInterface;
use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use Orqlog\Yacampaign\Domain\Model\JoinRecordInterface;
use Orqlog\Yacampaign\Domain\Exception\YacampaignException;

abstract class UserService
{

    /**
     * @var Orqlog\Yacampaign\Domain\Model\UserInterface
     */
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public abstract function getAddresses():array;

    public abstract function addAddress(AddressInterface $address):void ;

    public abstract function removeAddress(AddressInterface $address):void;
    
    public abstract function getDefaultAddress():?AddressInterface;

    public abstract function getJoinRecords():array;

    /**
     * Used to reconstitute the User object 
     */
    public function setJoinRecords(array $jRecords) :void 
    {
        $this->joinRecords = $jRecords;
    }

    public function joinCampaign(CampaignInterface $campaign):JoinRecordInterface
    {
        $campaignService = RegistryService::get(CampaignServiceInterface::class, ['campaign' => $campaign]);
        if (!$campaign->isOpen()) {
            throw new YacampaignException('The campaign is closed!', 1590541214);
        }
        
        if (!$campaignService->isQualified($this)) {
            throw new YacampaignException('You are not qualified to join this campaign!', 1590541309);
        }

        $joinRecord = RegistryService::get(JoinRecordInterface::class);
        $joinRecord->setCampaign($campaign);
        $joinRecord->setJoinTime(new \DateTime());
        $joinRecord->setPrizes($campaignService->decidePrize($this));

        array_push($this->joinRecords, $joinRecord);
        return $joinRecord;
    }

}