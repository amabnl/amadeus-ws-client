<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\RepriceOrder as RequestRepriceOrder;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\ReshopOrder as RequestReshopOrder;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\ReuseTicket as RequestReuseTicket;
use InvalidArgumentException;

/**
 * UpdateOrder
 *
 * Maps to the mutually exclusive UpdateOrder choice: RepriceOrder, ReshopOrder
 * or ReuseTickets. Exactly one branch must be populated.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class UpdateOrder
{
    /**
     * @var RepriceOrder|null
     */
    public $RepriceOrder;

    /**
     * @var ReshopOrder|null
     */
    public $ReshopOrder;

    /**
     * @var ReuseTickets|null
     */
    public $ReuseTickets;

    /**
     * @param RequestRepriceOrder|null $repriceOrder
     * @param RequestReshopOrder|null $reshopOrder
     * @param RequestReuseTicket[]|null $reuseTickets
     */
    public function __construct($repriceOrder, $reshopOrder, $reuseTickets)
    {
        if ($repriceOrder !== null) {
            $this->RepriceOrder = new RepriceOrder($repriceOrder->orderItemRefId);
        } elseif ($reshopOrder !== null) {
            $this->ReshopOrder = new ReshopOrder($reshopOrder);
        } elseif (!empty($reuseTickets)) {
            $this->ReuseTickets = new ReuseTickets(
                array_map(
                    static function (RequestReuseTicket $ticket) {
                        return new RetainedTicketDoc(
                            $ticket->ticketNumber,
                            $ticket->paxId,
                            $ticket->type
                        );
                    },
                    $reuseTickets
                )
            );
        } else {
            throw new InvalidArgumentException(
                'Travel_OrderReshop requires one of repriceOrder, reshopOrder or reuseTickets to be set'
            );
        }
    }
}
