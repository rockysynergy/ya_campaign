<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface UserInterface extends EntityInterface
{

    public function getAddresses():array;


    public function addAddress(AddressInterface $address):void;

    public function removeAddress(AddressInterface $address):void;
    
    public function getDefaultAddress():AddressInterface;

    public function getJoinRecords():array;

    public function addJoinRecord(JoinRecordInterface $joinRecord):void;

    public function removeJoinRecord(JoinRecordInterface $joinRecord):void;


}