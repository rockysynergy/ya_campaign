<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

use Orqlog\Yacampaign\Domain\Model\UserInterface;
use Orqlog\Yacampaign\Domain\Model\QualificationPolicyInterface;

abstract class AbstractQualificationPolicy extends AbstractEntity implements QualificationPolicyInterface
{

    public function isQualified(UserInterface $user) :bool
    {
        return false;
    }
}