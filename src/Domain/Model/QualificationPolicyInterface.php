<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface QualificationPolicyInterface extends EntityInterface
{
    public function isQualified(UserInterface $user) :bool;
}