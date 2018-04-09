<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * OriginatorDetails
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OriginatorDetails
{
    /**
     * @var string
     */
    public $originatorId;

    /**
     * @var string
     */
    public $inHouseIdentification1;

    /**
     * OriginatorDetails constructor.
     *
     * @param string $iataNumber
     * @param string $officeId
     */
    public function __construct($iataNumber, $officeId)
    {
        $this->originatorId = $iataNumber;
        $this->inHouseIdentification1 = $officeId;
    }
}
