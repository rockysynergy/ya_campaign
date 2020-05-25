<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface AddressInterface extends EntityInterface
{

    public function isDefault():bool;
}