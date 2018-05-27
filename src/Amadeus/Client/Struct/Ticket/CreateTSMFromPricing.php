<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketCreateTsmFromPricingOptions;

/**
 * Ticket_CreateTSMFromPricing request structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CreateTSMFromPricing extends CreateTSTFromPricing
{
    /**
     * @var PnrLocatorData
     */
    public $pnrLocatorData;

    /**
     * @var PsaList[]
     */
    public $psaList = [];

    /**
     * Ticket_CreateTSMFromPricing constructor.
     *
     * @param TicketCreateTsmFromPricingOptions $params
     */
    public function __construct(TicketCreateTsmFromPricingOptions $params)
    {
        parent::__construct($params);
    }
}
