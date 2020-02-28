<?php

namespace Amadeus\Client\Struct\Fare\GetFareFamilyDescription;

/**
 * Class ReferenceDetails
 * @package Amadeus\Client\Struct\Fare\GetFareFamilyDescription
 */
class ReferenceDetails
{
    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $value;

    /**
     * ReferenceDetails constructor.
     *
     * @param string $type
     * @param string $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
