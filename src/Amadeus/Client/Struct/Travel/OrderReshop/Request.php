<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\BookingRef as RequestBookingRef;
use Amadeus\Client\RequestOptions\TravelOrderReshopOptions;

/**
 * Request
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class Request
{
    /**
     * @var string
     */
    public $OrderItemRefID;

    /**
     * @var string|null
     */
    public $OrderActionContextText;

    /**
     * @var BookingRef[]|null
     */
    public $BookingRef;

    /**
     * @var UpdateOrder|null
     */
    public $UpdateOrder;

    /**
     * @param TravelOrderReshopOptions $options
     */
    public function __construct(TravelOrderReshopOptions $options)
    {
        $this->OrderItemRefID = $options->orderItemRefId;

        if ($options->orderActionContextText !== null) {
            $this->OrderActionContextText = $options->orderActionContextText;
        }

        if (!empty($options->bookingRefs)) {
            $this->BookingRef = array_map(
                static function (RequestBookingRef $bookingRef) {
                    return new BookingRef(
                        $bookingRef->bookingId,
                        $bookingRef->bookingEntityAirlineDesigCode,
                        $bookingRef->typeCode
                    );
                },
                $options->bookingRefs
            );
        }

        // UpdateOrder is optional: only include it when a reprice/reshop/reuse
        // action is requested. When omitted, the request only carries the
        // OrderItemRefID (and optional BookingRef) context.
        if ($options->repriceOrder !== null
            || $options->reshopOrder !== null
            || !empty($options->reuseTickets)
        ) {
            $this->UpdateOrder = new UpdateOrder(
                $options->repriceOrder,
                $options->reshopOrder,
                $options->reuseTickets
            );
        }
    }
}
