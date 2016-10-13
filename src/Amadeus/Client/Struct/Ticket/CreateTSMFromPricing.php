<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\TicketCreateTsmFromPricingOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Ticket_CreateTSMFromPricing request structure
 *
 * @package Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class CreateTSMFromPricing extends BaseWsMessage
{
    /**
     * @var PnrLocatorData
     */
    public $pnrLocatorData;

    /**
     * @var PsaList[]
     */
    public $psaList = [];

    /**
     * Ticket_CreateTSMFromPricing constructor.
     *
     * @param TicketCreateTsmFromPricingOptions $params
     */
    public function __construct(TicketCreateTsmFromPricingOptions $params)
    {
        foreach ($params->pricings as $pricing) {
            $tmp = new PsaList(
                $pricing->tsmNumber,
                ItemReference::REFTYPE_TSM
            );

            if (!empty($pricing->passengerReferences)) {
                $tmp->paxReference = new PaxReference();

                foreach ($pricing->passengerReferences as $passengerReference) {
                    $tmp->paxReference->refDetails[] = new RefDetails(
                        $passengerReference->id,
                        $passengerReference->type
                    );
                }
            }

            $this->psaList[] = $tmp;
        }

        if (!is_null($params->informationalRecordLocator)) {
            $this->pnrLocatorData = new PnrLocatorData($params->informationalRecordLocator);
        }
    }
}
