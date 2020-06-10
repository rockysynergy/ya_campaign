<?php

namespace Orqlog\Yacampaign\Domain\Model;

use Orqlog\Yacampaign\Domain\Service\UserServiceInterface;

interface QualificationPolicyInterface extends EntityInterface
{
    public function isQualified(UserServiceInterface $userService) :bool;
}