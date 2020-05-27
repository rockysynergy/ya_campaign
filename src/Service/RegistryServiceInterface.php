<?php

namespace Orqlog\Yacampaign\Service;


interface RegistryServiceInterface
{
    public function get(string $contract) :?object;
    public function set(string $contract, object $obj) :void;
}