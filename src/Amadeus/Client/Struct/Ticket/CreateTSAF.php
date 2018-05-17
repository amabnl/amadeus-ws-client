<?php

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketCreateTasfOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * TicketCreateTSAF
 *
 * Basic Request structure for the TicketCreateTSAF messages
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class CreateTSAF extends BaseWsMessage
{
    /**
     * CreateTSAF constructor.
     *
     * @param TicketCreateTasfOptions $requestOptions
     */
    public function __construct($requestOptions)
    {
        //
    }
}
