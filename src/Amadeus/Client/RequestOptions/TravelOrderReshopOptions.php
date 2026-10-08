<?php

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\BookingRef;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\RepriceOrder;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\ReshopOrder;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\ReuseTicket;

/**
 * Travel_OrderReshop Request Options
 *
 * The OrderReshop transaction passes new shopping requests to an airline to
 * replace existing order or order items in an Order, reprice an Order or reuse
 * a ticket. The airline responds with product offers within the context of the
 * existing Order (typically followed by an OrderChangeRQ or OrderCancelRQ).
 *
 * Only $orderItemRefId is mandatory. The UpdateOrder action is optional: when
 * one of $repriceOrder, $reshopOrder or $reuseTickets is provided it is emitted
 * as the (mutually exclusive) UpdateOrder choice; when all are omitted no
 * UpdateOrder element is sent and the request carries only the OrderItemRefID
 * (and optional BookingRef) context.
 *
 * @package Amadeus\Client\RequestOptions
 */
class TravelOrderReshopOptions extends AbstractTravelOptions
{
    /**
     * Reference to the existing Order Item the Seller wants to add, update or
     * delete (Request/OrderItemRefID). Required.
     *
     * @var string
     */
    public $orderItemRefId;

    /**
     * Reference to PADIS codeset REA providing context for the requested change.
     *
     * @var string|null
     */
    public $orderActionContextText;

    /**
     * Information related to a booking or reservation.
     *
     * @var BookingRef[]|null
     */
    public $bookingRefs;

    /**
     * Reprice the entire Order or a specific Order Item.
     *
     * @var RepriceOrder|null
     */
    public $repriceOrder;

    /**
     * Reshop items within an Order (add offer items, delete order items, ...).
     *
     * @var ReshopOrder|null
     */
    public $reshopOrder;

    /**
     * Retained tickets for reusing the passenger's credit.
     *
     * @var ReuseTicket[]|null
     */
    public $reuseTickets;
}
