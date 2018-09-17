<?php

namespace Amadeus\Client\Struct\Ticket\CreateTASF;

class CriteriaDetails
{
    /**
     * @var string
     */
    public $attributeType;

    /**
     * CriteriaDetails constructor.
     *
     * @param string $type
     */
    public function __construct($type)
    {
        $this->attributeType = $type;
    }
}
