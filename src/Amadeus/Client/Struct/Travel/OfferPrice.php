<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelOfferPriceOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Travel_OfferPrice message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class OfferPrice extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var OfferPrice\Request
     */
    public $Request;

    public function __construct(TravelOfferPriceOptions $options)
    {
        $this->Party = new Party($options->party);
        $this->Request = new OfferPrice\Request($options->dataLists, $options->pricedOffer);
    }
}
