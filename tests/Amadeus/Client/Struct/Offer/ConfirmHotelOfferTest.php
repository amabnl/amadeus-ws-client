<?php
/**
 * amadeus-ws-client
 *
 * Copyright 2015 Amadeus Benelux NV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Test\Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\Offer\PaymentDetails;
use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\Struct\Offer\ConfirmHotel;
use Amadeus\Client\Struct\Offer\PassengerReference;
use Test\Amadeus\BaseTestCase;

/**
 * ConfirmHotelOfferTest
 *
 * @package Test\Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class ConfirmHotelOfferTest extends BaseTestCase
{
    public function testCanConfirmHotelOfferFullOption()
    {
        $opt = new OfferConfirmHotelOptions([
            'recordLocator' => 'ABC123',
            'offerReference' => 2,
            'passengers' => [1],
            'paymentType' => OfferConfirmHotelOptions::PAYMENT_GUARANTEED,
            'formOfPayment' => OfferConfirmHotelOptions::FOP_CREDIT_CARD,
            'paymentDetails' => new PaymentDetails([
                'ccCardNumber' => '4444333322221111',
                'ccCardHolder' => 'David Bowie',
                'ccExpiry' => '1117',
                'ccVendor' => 'AX',
            ])
        ]);

        $msg = new ConfirmHotel($opt);

        $this->assertEquals('ABC123', $msg->pnrInfo->reservation->controlNumber);
        $this->assertEquals(ConfirmHotel\Reservation::CONTROLTYPE_PNR_IDENTIFICATION, $msg->pnrInfo->reservation->controlType);
        $this->assertEquals(2, $msg->roomStayData[0]->tattooReference->reference->number);
        $this->assertEquals(ConfirmHotel\Reference::QUAL_OFFER_TATTOO, $msg->roomStayData[0]->tattooReference->reference->qualifier);

        $this->assertEquals(PassengerReference::TYPE_BOOKING_HOLDER_OCCUPANT, $msg->roomStayData[0]->globalBookingInfo->representativeParties[0]->occupantList->passengerReference->type);
        $this->assertEquals(1, $msg->roomStayData[0]->globalBookingInfo->representativeParties[0]->occupantList->passengerReference->value);

        $this->assertEquals(ConfirmHotel\ReferenceDetails::TYPE_BOOKING_CODE, $msg->roomStayData[0]->roomList[0]->roomRateDetails->hotelProductReference[0]->referenceDetails[0]->type);
    }
}
