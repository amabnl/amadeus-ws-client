<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\ReshopOrder as RequestReshopOrder;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\UpdatePax as RequestUpdatePax;
use InvalidArgumentException;

/**
 * ReshopOrder
 *
 * Function used to reshop items within an Order. Maps to the ReshopOrderType
 * choice: ServiceOrder (AddOfferItems and/or DeleteOrderItem), UpdatePax, or
 * UpdatePaxName.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class ReshopOrder
{
    /**
     * @var ServiceOrder|null
     */
    public $ServiceOrder;

    /**
     * @var UpdatePax[]|null
     */
    public $UpdatePax;

    /**
     * @var UpdatePaxName|null
     */
    public $UpdatePaxName;

    /**
     * @param RequestReshopOrder $reshopOrder
     */
    public function __construct(RequestReshopOrder $reshopOrder)
    {
        if ($reshopOrder->addOfferItems !== null || !empty($reshopOrder->deleteOrderItems)) {
            $this->ServiceOrder = new ServiceOrder(
                $reshopOrder->addOfferItems,
                $reshopOrder->deleteOrderItems
            );
        } elseif (!empty($reshopOrder->updatePax)) {
            $this->UpdatePax = array_map(
                static function (RequestUpdatePax $updatePax) {
                    return new UpdatePax($updatePax);
                },
                $reshopOrder->updatePax
            );
        } elseif ($reshopOrder->updatePaxName !== null) {
            $this->UpdatePaxName = new UpdatePaxName($reshopOrder->updatePaxName);
        } else {
            throw new InvalidArgumentException(
                'ReshopOrder requires one of addOfferItems, deleteOrderItems, updatePax or updatePaxName'
            );
        }
    }
}
