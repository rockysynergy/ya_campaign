<?php

namespace Orqlog\Yacampaign\Service;


interface RegistryServiceInterface
{
    public function get(string $contract) :object;
}