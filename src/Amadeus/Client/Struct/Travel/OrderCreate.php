<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOrderCreateOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Travel_OrderCreate message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OrderCreate extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var OrderCreate\Request
     */
    public $Request;

    public function __construct(TravelOrderCreateOptions $options)
    {
        $this->Party = new Party($options->party);
        $this->Request = new OrderCreate\Request($options->dataLists, $options->pricedOffer);
    }
}
