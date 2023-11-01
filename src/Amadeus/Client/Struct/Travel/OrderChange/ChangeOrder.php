<?php

namespace Amadeus\Client\Struct\Travel\OrderChange;

/**
 * ChangeOrder
 *
 * @package Amadeus\Client\Struct\Travel\OrderRetrieve
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class ChangeOrder
{
    /**
     * @var AcceptChange
     */
    public $AcceptChange;

    /**
     * @var UpdateOrderItem
     */
    public $UpdateOrderItem;

    public function setAcceptChange(AcceptChange $acceptChange)
    {
        $this->AcceptChange = $acceptChange;
    }

    public function setUpdateOrderItem(UpdateOrderItem $updateOrderItem)
    {
        $this->UpdateOrderItem = $updateOrderItem;
    }
}
