<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOrderRetrieveOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Travel_OrderRetrieve message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderRetrieve extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var OrderRetrieve\Request
     */
    public $Request;

    public function __construct(TravelOrderRetrieveOptions $options)
    {
        $this->Party = new Party($options->party);
        $this->Request = new OrderRetrieve\Request(
            new OrderRetrieve\OrderFilterCriteria(
                new \Amadeus\Client\Struct\Travel\Order(
                    $options->orderId,
                    $options->ownerCode
                )
            )
        );
    }
}
