<?php

namespace Orqlog\Yacampaign\Service;

use CampaignServiceInterface;
use Orqlog\Yacampaign\Domain\Service\CampaignService;

class RegistryService
{
    /**
     * @var array
     */
    private static $map = [CampaignServiceInterface::class =>  CampaignService::class];

    public static function get(string $contract, $constructArgs = []) :?object
    {
        if (isset(self::$map[$contract])) {
            $class = self::$map[$contract];
            if (count($constructArgs)<1) {
                $obj = new $class();
            } else {
                $r = new \ReflectionClass($class);
                $obj = $r->newInstanceArgs($constructArgs);
            }

            return $obj;
        }

    }

    public static function set(string $contract, string $fqClass) :void
    {
        self::$map[$contract] = $fqClass;
    }
}