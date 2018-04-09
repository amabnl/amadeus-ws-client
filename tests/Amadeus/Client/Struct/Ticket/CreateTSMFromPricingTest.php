<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\Ticket\PassengerReference;
use Amadeus\Client\RequestOptions\Ticket\Pricing;
use Amadeus\Client\RequestOptions\TicketCreateTsmFromPricingOptions;
use Amadeus\Client\Struct\Ticket\CreateTSMFromPricing;
use Amadeus\Client\Struct\Ticket\ItemReference;
use Amadeus\Client\Struct\Ticket\RefDetails;
use Test\Amadeus\BaseTestCase;

/**
 * CreateTSMFromPricingTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CreateTSMFromPricingTest extends BaseTestCase
{
    public function testCanMakeTstFromPricingWithPassengerReference()
    {
        $msg = new CreateTSMFromPricing(
            new TicketCreateTsmFromPricingOptions([
                'pricings' => [
                    new Pricing([
                        'tsmNumber' => 2,
                        'passengerReferences' => [
                            new PassengerReference([
                                'id' => 1,
                                'type' => PassengerReference::TYPE_PASSENGER
                            ])
                        ]
                    ])
                ]
            ])
        );

        $this->assertEquals(1, count($msg->psaList));
        $this->assertEquals(2, $msg->psaList[0]->itemReference->uniqueReference);
        $this->assertEquals(ItemReference::REFTYPE_TSM, $msg->psaList[0]->itemReference->referenceType);

        $this->assertEquals(1, count($msg->psaList[0]->paxReference->refDetails));
        $this->assertEquals(1, $msg->psaList[0]->paxReference->refDetails[0]->refNumber);
        $this->assertEquals(RefDetails::QUAL_PASSENGER, $msg->psaList[0]->paxReference->refDetails[0]->refQualifier);
    }

    public function testCanMakeTsmFromPricingWithPnrInfo()
    {
        // !!! This PNR record locator is used for tracing purpose, no internal retrieve. !!!

        $msg = new CreateTSMFromPricing(
            new TicketCreateTsMFromPricingOptions([
                'informationalRecordLocator' => 'ABC123',
                'pricings' => [
                    new Pricing([
                        'tsmNumber' => 3
                    ])
                ]
            ])
        );

        $this->assertEquals('ABC123', $msg->pnrLocatorData->reservationInformation->controlNumber);
    }
}
