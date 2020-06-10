<?php
namespace Orqlog\Yacampaign\Domain\Service;

use Orqlog\Yacampaign\Domain\Model\AddressInterface;
use Orqlog\Yacampaign\Domain\Model\CampaignInterface;
use Orqlog\Yacampaign\Domain\Model\JoinRecordInterface;

interface UserServiceInterface 
{
    
    public function getAddresses():array;

    public function addAddress(AddressInterface $address):void;

    public function removeAddress(AddressInterface $address):void;
    
    public function getDefaultAddress():?AddressInterface;

    public function getJoinRecords():array;

    public function joinCampaign(CampaignInterface $campaign):JoinRecordInterface;
}