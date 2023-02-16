<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOrderCancelOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Travel_OrderCancel message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderCancel extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var OrderRetrieve\Request
     */
    public $Request;

    public function __construct(TravelOrderCancelOptions $options)
    {
        $this->Party = new Party($options->party);
        $this->Request = new OrderCancel\Request(
            new Order(
                $options->orderId,
                $options->ownerCode
            )
        );
    }
}
