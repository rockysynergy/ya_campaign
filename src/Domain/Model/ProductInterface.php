<?php

namespace Orqlog\Yacampaign\Domain\Model;

interface ProductInterface extends EntityInterface
{
    public function getPrice():float;
}