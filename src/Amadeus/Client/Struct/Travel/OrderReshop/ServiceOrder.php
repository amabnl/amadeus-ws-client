<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\AddOfferItems as RequestAddOfferItems;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\DeleteOrderItem as RequestDeleteOrderItem;

/**
 * ServiceOrder
 *
 * Functions to request additions (AddOfferItems) and/or deletions
 * (DeleteOrderItem) of Order Items in a specific Order.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class ServiceOrder
{
    /**
     * @var AddOfferItems|null
     */
    public $AddOfferItems;

    /**
     * @var DeleteOrderItem[]|null
     */
    public $DeleteOrderItem;

    /**
     * @param RequestAddOfferItems|null $addOfferItems
     * @param RequestDeleteOrderItem[]|string[]|null $deleteOrderItems
     */
    public function __construct($addOfferItems, $deleteOrderItems)
    {
        if ($addOfferItems !== null) {
            $this->AddOfferItems = new AddOfferItems($addOfferItems);
        }

        if (!empty($deleteOrderItems)) {
            $this->DeleteOrderItem = $this->buildDeleteOrderItems($deleteOrderItems);
        }
    }

    /**
     * @param RequestDeleteOrderItem[]|string[] $deleteOrderItems
     * @return DeleteOrderItem[]
     */
    private function buildDeleteOrderItems(array $deleteOrderItems)
    {
        $items = [];

        foreach ($deleteOrderItems as $deleteOrderItem) {
            if ($deleteOrderItem instanceof RequestDeleteOrderItem) {
                $items[] = new DeleteOrderItem(
                    $deleteOrderItem->orderItemRefId,
                    $deleteOrderItem->retainServiceId
                );
            } else {
                $items[] = new DeleteOrderItem($deleteOrderItem, null);
            }
        }

        return $items;
    }
}
