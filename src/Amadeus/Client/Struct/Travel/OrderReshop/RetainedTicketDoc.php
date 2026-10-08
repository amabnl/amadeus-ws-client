<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * RetainedTicketDoc
 *
 * Per-passenger information for reusable credit.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class RetainedTicketDoc
{
    /**
     * @var string
     */
    public $TicketNumber;

    /**
     * @var string
     */
    public $PaxID;

    /**
     * @var string|null
     */
    public $Type;

    /**
     * @param string $ticketNumber
     * @param string $paxId
     * @param string|null $type
     */
    public function __construct($ticketNumber, $paxId, $type = null)
    {
        $this->TicketNumber = $ticketNumber;
        $this->PaxID = $paxId;

        if ($type !== null) {
            $this->Type = $type;
        }
    }
}
