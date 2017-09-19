<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\DocIssuance;

/**
 * StockTicketNumberDetails
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StockTicketNumberDetails
{
    const QUAL_STOCK_CONTROL_NR = "S";

    /**
     * @var string
     */
    public $qualifier = self::QUAL_STOCK_CONTROL_NR;

    /**
     * @var string
     */
    public $controlNumber;

    /**
     * StockTicketNumberDetails constructor.
     *
     * @param string $controlNumber
     */
    public function __construct($controlNumber)
    {
        $this->controlNumber = $controlNumber;
    }
}
