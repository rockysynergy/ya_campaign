<?php

namespace Orqlog\Yacampaign\Domain\Model\Impl;

/**
 * @value 
 */
class JoinResult
{
    /**
     * @var string
     */
    protected $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function getResult()
    {
        return $this->result;
    }
}