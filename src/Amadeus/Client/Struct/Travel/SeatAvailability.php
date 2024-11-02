<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelSeatAvailabilityOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Travel\SeatAvailability\Offer;

/**
 * Travel_SeatAvailability message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SeatAvailability extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var SeatAvailability\Request
     */
    public $Request;

    public function __construct(TravelSeatAvailabilityOptions $options)
    {
        $this->Party = new Party($options->party);

        $coreRequest = new SeatAvailability\CoreRequest();

        if (($orderId = $options->orderId) && ($ownerCode = $options->ownerCode)) {
            $coreRequest->setOrder(
                new Order(
                    $orderId,
                    $ownerCode
                )
            );
        } elseif (($offerItemId = $options->offerItemId) && ($ownerCode = $options->ownerCode)) {
            $coreRequest->setOffer(new Offer($offerItemId, $ownerCode));
        } else {
            throw new \InvalidArgumentException('Invalid combination of arguments at TravelSeatAvailabilityOptions');
        }

        $this->Request = new SeatAvailability\Request($coreRequest);

        if ($shoppingResponseId = $options->shoppingResponseId) {
            $this->Request->setShoppingResponse(new ShoppingResponse($shoppingResponseId));
        }
    }
}
