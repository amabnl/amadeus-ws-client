<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * LastItemsDetails
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class LastItemsDetails
{
    /**
     * @var string
     */
    public $lastItemIdentifier;

    /**
     * LastItemsDetails constructor.
     *
     * @param string $lastItemIdentifier
     */
    public function __construct($lastItemIdentifier)
    {
        $this->lastItemIdentifier = $lastItemIdentifier;
    }
}
