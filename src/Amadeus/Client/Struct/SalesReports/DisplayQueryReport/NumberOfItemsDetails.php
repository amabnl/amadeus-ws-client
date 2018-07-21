<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * NumberOfItemsDetails
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class NumberOfItemsDetails
{
    /**
     * @var int
     */
    public $numberOfItems;

    /**
     * NumberOfItemsDetails constructor.
     *
     * @param int $amount
     */
    public function __construct($amount)
    {
        $this->numberOfItems = $amount;
    }
}
