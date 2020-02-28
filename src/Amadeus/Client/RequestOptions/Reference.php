<?php

namespace Amadeus\Client\RequestOptions;

/**
 * Class Reference
 * @package Amadeus\Client\RequestOptions
 */
class Reference
{
    const TYPE_TST = 'T';
    const TYPE_RECOMMENDATION = 'REC';
    const TYPE_FARE_COMPONENT = 'FC';

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $value;

    /**
     * Reference constructor.
     * @param string $type
     * @param int $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }
}
