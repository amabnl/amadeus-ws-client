<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOrderChangeOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Travel_OrderChange message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderChange extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var OrderChange\Request
     */
    public $Request;

    public function __construct(TravelOrderChangeOptions $options)
    {
        $this->Party = new Party($options->party);
        $this->Request = new OrderChange\Request(
            $options->acceptChange,
            $options->updateOrderItem,
            $options->dataLists,
            new Order(
                $options->orderId,
                $options->ownerCode
            )
        );
    }
}
