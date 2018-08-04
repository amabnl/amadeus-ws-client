<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * TransactionDetails
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TransactionDetails
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $issueIndicator;

    /**
     * TransactionDetails constructor.
     *
     * @param string|null $type
     * @param string|null $code
     * @param string|null $issueIndicator
     */
    public function __construct($type, $code, $issueIndicator)
    {
        $this->code = $code;
        $this->type = $type;
        $this->issueIndicator = $issueIndicator;
    }
}
