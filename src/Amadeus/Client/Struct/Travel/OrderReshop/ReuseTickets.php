<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * ReuseTickets
 *
 * Retained tickets for reusing the passenger's credit.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class ReuseTickets
{
    /**
     * @var RetainedTicketDoc[]
     */
    public $RetainedTicketDoc;

    /**
     * @param RetainedTicketDoc[] $retainedTicketDocs
     */
    public function __construct(array $retainedTicketDocs)
    {
        $this->RetainedTicketDoc = $retainedTicketDocs;
    }
}
