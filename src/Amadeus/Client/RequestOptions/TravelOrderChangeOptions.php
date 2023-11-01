<?php

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions\Travel\DataList;
use Amadeus\Client\RequestOptions\Travel\OrderChange\AcceptChange;
use Amadeus\Client\RequestOptions\Travel\OrderChange\UpdateOrderItem;

/**
 * Travel_OrderChange Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TravelOrderChangeOptions extends AbstractTravelOptions
{
    /**
     * @var AcceptChange|null
     */
    public $acceptChange = null;

    /**
     * @var UpdateOrderItem|null
     */
    public $updateOrderItem = null;

    /**
     * @var DataList[]
     */
    public $dataLists;

    /**
     * @var string
     */
    public $orderId;

    /**
     * @var string
     */
    public $ownerCode;
}
