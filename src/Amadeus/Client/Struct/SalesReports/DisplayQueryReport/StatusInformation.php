<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * StatusInformation
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StatusInformation
{
    /**
     * Domestic sale
     */
    const SALESIND_DOMESTIC = "DOM";
    /**
     * International sale
     */
    const SALESIND_INTERNATIONAL = "INT";
    /**
     * Voided document
     */
    const SALESIND_VOIDED_DOCUMENT = "V";

    /**
     * self::SALESIND_*
     *
     * @var string
     */
    public $type;

    /**
     * StatusInformation constructor.
     *
     * @param string $indicator self::SALESIND_*
     */
    public function __construct($indicator)
    {
        $this->type = $indicator;
    }
}
