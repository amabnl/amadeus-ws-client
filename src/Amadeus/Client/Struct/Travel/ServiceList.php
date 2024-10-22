<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\TravelServiceListOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Travel\ServiceList\Offer;
use Amadeus\Client\Struct\Travel\ServiceList\OfferItem;
use Amadeus\Client\Struct\Travel\ServiceList\Service;

/**
 * Travel_ServiceList message structure
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class ServiceList extends BaseWsMessage
{
    /**
     * @var Party
     */
    public $Party;

    /**
     * @var ServiceList\Request
     */
    public $Request;

    public function __construct(TravelServiceListOptions $options)
    {
        $this->Party = new Party($options->party);

        $coreRequest = new ServiceList\CoreRequest();

        if (($orderId = $options->orderId) && ($ownerCode = $options->ownerCode)) {
            $coreRequest->setOrder(
                new Order(
                    $orderId,
                    $ownerCode
                )
            );
        } elseif (($offerId = $options->offerId)
            && ($offerItemId = $options->offerItemId)
            && ($ownerCode = $options->ownerCode)
            && ($serviceId = $options->serviceId)
        ) {
            $coreRequest->setOffer(
                new Offer(
                    $offerId,
                    $ownerCode,
                    new OfferItem(
                        $offerItemId,
                        $ownerCode,
                        new Service($serviceId)
                    )
                )
            );
        } else {
            throw new \InvalidArgumentException('Invalid combination of arguments at TravelServiceListOptions');
        }

        $this->Request = new ServiceList\Request($coreRequest);

        if ($shoppingResponseId = $options->shoppingResponseId) {
            $this->Request->setShoppingResponse(new ShoppingResponse($shoppingResponseId));
        }
    }
}
