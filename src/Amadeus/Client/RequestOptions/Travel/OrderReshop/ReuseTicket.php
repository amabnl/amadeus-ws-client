<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * ReuseTicket - request options
 *
 * Per-passenger information for reusing an unused ticket's credit on a new
 * ticket (ReuseTickets/RetainedTicketDoc).
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class ReuseTicket extends LoadParamsFromArray
{
    /**
     * Reference ticket for reusable credit.
     *
     * @var string
     */
    public $ticketNumber;

    /**
     * Uniquely identifies the passenger within the message.
     *
     * @var string
     */
    public $paxId;

    /**
     * Ticket/document reference codeset.
     *
     * @var string|null
     */
    public $type;
}
