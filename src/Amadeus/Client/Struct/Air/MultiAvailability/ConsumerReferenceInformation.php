<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * ConsumerReferenceInformation
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConsumerReferenceInformation
{
    /**
     * @var CustomerReferences[]
     */
    public $customerReferences = [];

    /**
     * ConsumerReferenceInformation constructor.
     *
     * @param string $customerNumber
     */
    public function __construct($customerNumber)
    {
        $this->customerReferences[] = new CustomerReferences($customerNumber);
    }
}
