<?php

namespace Orqlog\Yacampaign\Service;

use Orqlog\Yacampaign\Domain\Model\Impl\BaseJoinRecord;
use Orqlog\Yacampaign\Domain\Model\JoinRecordInterface;

class RegistryService implements RegistryServiceInterface
{
    /**
     * @var array
     */
    protected $map = [];

    public function __construct()
    {
        $this->map[JoinRecordInterface::class] = new BaseJoinRecord();
    }

    public function get(string $contract) :?object
    {
        if (isset($this->map[$contract])) {
            return $this->map[$contract];
        }

    }

    public function set(string $contract, object $obj) :void
    {
        $this->map[$contract] = $obj;
    }
}