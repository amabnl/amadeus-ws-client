<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * ReshopOrder - request options
 *
 * Function used to reshop items within an Order. Maps to the ReshopOrderType
 * choice in the WSDL, which is one of:
 *  - ServiceOrder (AddOfferItems and/or DeleteOrderItem)
 *  - UpdatePax
 *  - UpdatePaxName
 *
 * $addOfferItems and $deleteOrderItems both belong to the ServiceOrder branch
 * and may be combined. $updatePax and $updatePaxName are mutually exclusive
 * alternatives to the ServiceOrder branch.
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class ReshopOrder extends LoadParamsFromArray
{
    /**
     * ServiceOrder / AddOfferItems: request new Offer Items for the Order.
     *
     * @var AddOfferItems|null
     */
    public $addOfferItems;

    /**
     * ServiceOrder / DeleteOrderItem: Order Item references to remove.
     *
     * Each entry may be a plain OrderItemRefID string or a DeleteOrderItem object.
     *
     * @var DeleteOrderItem[]|string[]|null
     */
    public $deleteOrderItems;

    /**
     * UpdatePax: add / remove / update passenger details.
     *
     * @var UpdatePax[]|null
     */
    public $updatePax;

    /**
     * UpdatePaxName: request whether fees apply to a passenger name correction.
     *
     * @var UpdatePaxName|null
     */
    public $updatePaxName;
}
