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
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConfirmHotelOfferTest extends BaseTestCase
{
    public function testCanConfirmHotelOfferFullOption()
    {
        $opt = new OfferConfirmHotelOptions([
            'recordLocator' => 'ABC123',
            'offerReference' => 2,
            'passengers' => [1],
            'originatorId' => '123456',
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

        $this->assertEquals(1, count($msg->roomStayData));
        $this->assertEquals(2, $msg->roomStayData[0]->tattooReference->reference->number);
        $this->assertEquals(ConfirmHotel\TattooReference::SEGMENT_NAME_HOTEL_AUX, $msg->roomStayData[0]->tattooReference->segmentName);
        $this->assertEquals(ConfirmHotel\Reference::QUAL_OFFER_TATTOO, $msg->roomStayData[0]->tattooReference->reference->qualifier);

        $this->assertEquals(PassengerReference::TYPE_PAXREF, $msg->roomStayData[0]->globalBookingInfo->representativeParties[0]->occupantList->passengerReference->type);
        $this->assertEquals(1, $msg->roomStayData[0]->globalBookingInfo->representativeParties[0]->occupantList->passengerReference->value);

        $this->assertEquals('123456', $msg->roomStayData[0]->globalBookingInfo->bookingSource->originIdentification->originatorId);

        //$this->assertEquals(ConfirmHotel\ReferenceDetails::TYPE_BOOKING_CODE, $msg->roomStayData[0]->roomList[0]->roomRateDetails->hotelProductReference[0]->referenceDetails->type);
        $this->assertNull($msg->roomStayData[0]->roomList[0]->roomRateDetails);

        $this->assertEquals(ConfirmHotel\PaymentDetails::FOP_CREDIT_CARD, $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->paymentInfo->paymentDetails->formOfPaymentCode);
        $this->assertEquals(ConfirmHotel\PaymentDetails::PAYMENT_GUARANTEED, $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->paymentInfo->paymentDetails->paymentType);
        $this->assertEquals(ConfirmHotel\PaymentDetails::SERVICE_HOTEL, $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->paymentInfo->paymentDetails->serviceToPay);

        $this->assertEquals('4444333322221111', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->cardNumber);
        $this->assertEquals('David Bowie', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->ccHolderName);
        $this->assertEquals('1117', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->expiryDate);
        $this->assertEquals('AX', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->vendorCode);
    }

    public function testCanConfirmHotelOfferNoPassengersWithOriginator()
    {
        $opt = new OfferConfirmHotelOptions([
            'recordLocator' => 'ABC123',
            'offerReference' => 2,
            'originatorId' => '123456',
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

        $this->assertEquals(1, count($msg->roomStayData));
        $this->assertEquals(2, $msg->roomStayData[0]->tattooReference->reference->number);
        $this->assertEquals(ConfirmHotel\TattooReference::SEGMENT_NAME_HOTEL_AUX, $msg->roomStayData[0]->tattooReference->segmentName);
        $this->assertEquals(ConfirmHotel\Reference::QUAL_OFFER_TATTOO, $msg->roomStayData[0]->tattooReference->reference->qualifier);

        $this->assertEquals('123456', $msg->roomStayData[0]->globalBookingInfo->bookingSource->originIdentification->originatorId);

        //$this->assertEquals(ConfirmHotel\ReferenceDetails::TYPE_BOOKING_CODE, $msg->roomStayData[0]->roomList[0]->roomRateDetails->hotelProductReference[0]->referenceDetails->type);
        $this->assertNull($msg->roomStayData[0]->roomList[0]->roomRateDetails);

        $this->assertEquals(ConfirmHotel\PaymentDetails::FOP_CREDIT_CARD, $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->paymentInfo->paymentDetails->formOfPaymentCode);
        $this->assertEquals(ConfirmHotel\PaymentDetails::PAYMENT_GUARANTEED, $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->paymentInfo->paymentDetails->paymentType);
        $this->assertEquals(ConfirmHotel\PaymentDetails::SERVICE_HOTEL, $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->paymentInfo->paymentDetails->serviceToPay);

        $this->assertEquals('4444333322221111', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->cardNumber);
        $this->assertEquals('David Bowie', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->ccHolderName);
        $this->assertEquals('1117', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->expiryDate);
        $this->assertEquals('AX', $msg->roomStayData[0]->roomList[0]->guaranteeOrDeposit->groupCreditCardInfo->creditCardInfo->ccInfo->vendorCode);
    }

    public function testCanConfirmHotelOfferWithUnsupportedFop()
    {
        $this->setExpectedException(
            'Amadeus\Client\Struct\InvalidArgumentException',
            'Hotel Offer Confirm Form of Payment ADV is not yet supported'
        );
        $opt = new OfferConfirmHotelOptions([
            'recordLocator' => 'ABC123',
            'offerReference' => 2,
            'passengers' => [1],
            'originatorId' => '123456',
            'paymentType' => OfferConfirmHotelOptions::PAYMENT_GUARANTEED,
            'formOfPayment' => OfferConfirmHotelOptions::FOP_ADVANCE_DEPOSIT,
            'paymentDetails' => new PaymentDetails([
                'ccCardNumber' => '4444333322221111',
                'ccCardHolder' => 'David Bowie',
                'ccExpiry' => '1117',
                'ccVendor' => 'AX',
            ])
        ]);

        new ConfirmHotel($opt);
    }
}
