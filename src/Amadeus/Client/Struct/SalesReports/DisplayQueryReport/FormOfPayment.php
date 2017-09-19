<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * FormOfPayment
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FormOfPayment
{
    /**
     * On behalf of/in exchange for a document previously issued by a Sales Agent
     */
    const FOP_ISSUED_BY_AGENT = "AGT";
    /**
     * Cash
     */
    const FOP_CASH = "CA";
    /**
     * Credit Card
     */
    const FOP_CREDIT_CARD = "CC";
    /**
     * Check
     */
    const FOP_CHECK = "CK";
    /**
     * Government transportation request
     */
    const FOP_GOVERNMENT_TRANSPORTATION_REQ = "GR";
    /**
     * Miscellaneous
     */
    const FOP_MISCELLANEOUS = "MS";
    /**
     * Non-refundable (refund restricted)
     */
    const FOP_NON_REFUNDABLE = "NR";
    /**
     * Prepaid Ticket Advice (PTA)
     */
    const FOP_PREPAID_TICKET_ADVICE = "PT";
    /**
     * Single government transportation request
     */
    const FOP_SINGLE_GOVERNMENT_TRANSPORTATION_REQ = "SGR";
    /**
     * United Nations Transportation Request
     */
    const FOP_UNITED_NATIONS_TRANSPORTATION_REQ = "UN";

    /**
     * self::FOP_*
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $vendorCode;

    /**
     * FormOfPayment constructor.
     *
     * @param string $type self::FOP_*
     * @param string $vendor
     */
    public function __construct($type, $vendor)
    {
        $this->type = $type;
        $this->vendorCode = $vendor;
    }
}
