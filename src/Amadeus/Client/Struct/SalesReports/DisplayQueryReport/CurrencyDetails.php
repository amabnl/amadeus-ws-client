<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * CurrencyDetails
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CurrencyDetails
{
    const CURRENCY_TARGET = 3;

    const CURRENCY_REFERENCE = 2;

    /**
     * @var int
     */
    public $currencyQualifier;

    /**
     * @var string
     */
    public $currencyIsoCode;

    /**
     * CurrencyDetails constructor.
     *
     * @param int $type
     * @param string $currency
     */
    public function __construct($type, $currency)
    {
        $this->currencyQualifier = $type;
        $this->currencyIsoCode = $currency;
    }
}
