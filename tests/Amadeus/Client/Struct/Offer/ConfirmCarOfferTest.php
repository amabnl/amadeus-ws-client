<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\Offer\CarLocationInfo;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\Struct\Offer\ConfirmCar;
use Amadeus\Client\Struct\Offer\Reference;
use Test\Amadeus\BaseTestCase;

/**
 * ConfirmCarOfferTest
 *
 * @package Test\Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConfirmCarOfferTest extends BaseTestCase
{
    public function testCanMakeMessageWithOverridePickUpDropOff()
    {
        $opt = new OfferConfirmCarOptions([
            'passengerTattoo' => 1,
            'offerTattoo' => 2,
            'recordLocator' => 'ABC123',
            'pickUpInfo' => new CarLocationInfo([
                'address' => 'RUE DE LA LIBERATION',
                'city' => 'NICE',
                'zipCode' => '06000',
                'countryCode' => 'FR',
                'phoneNumber' => '1234567890'
            ]),
            'dropOffInfo' => new CarLocationInfo([
                'address' => 'ROUTE DE VALBONNE',
                'city' => 'BIOT',
                'zipCode' => '06410',
                'countryCode' => 'FR',
                'phoneNumber' => '0123456789'
            ]),
        ]);

        $msg = new ConfirmCar($opt);

        $this->assertEquals(1, $msg->pnrInfo->paxTattooNbr->referenceDetails->value);
        $this->assertEquals(ConfirmCar\ReferenceDetails::TYPE_PASSENGER_TATTOO, $msg->pnrInfo->paxTattooNbr->referenceDetails->type);

        $this->assertEquals(2, $msg->pnrInfo->tattooReference->reference->value);
        $this->assertEquals(Reference::TYPE_OFFER_TATTOO, $msg->pnrInfo->tattooReference->reference->type);
        $this->assertEquals(ConfirmCar\TattooReference::SEGNAME_CAR, $msg->pnrInfo->tattooReference->segmentName);
        $this->assertEquals('ABC123', $msg->pnrInfo->pnrRecLoc->reservation->controlNumber);

        $this->assertCount(2, $msg->deliveryAndCollection);

        $this->assertEquals(ConfirmCar\AddressUsageDetails::PURPOSE_COLLECTION, $msg->deliveryAndCollection[0]->addressDeliveryCollection->addressUsageDetails->purpose);
        $this->assertEquals(ConfirmCar\AddressDetails::FORMAT_UNSTRUCTURED, $msg->deliveryAndCollection[0]->addressDeliveryCollection->addressDetails->format);
        $this->assertEquals('RUE DE LA LIBERATION', $msg->deliveryAndCollection[0]->addressDeliveryCollection->addressDetails->line1);

        $this->assertEquals('NICE', $msg->deliveryAndCollection[0]->addressDeliveryCollection->city);
        $this->assertEquals('06000', $msg->deliveryAndCollection[0]->addressDeliveryCollection->zipCode);
        $this->assertEquals('FR', $msg->deliveryAndCollection[0]->addressDeliveryCollection->countryCode);

        $this->assertEquals(ConfirmCar\PhoneNumber::TYPE_PHONE_NUMBER, $msg->deliveryAndCollection[0]->phoneNumber->phoneOrEmailType);
        $this->assertEquals('1234567890', $msg->deliveryAndCollection[0]->phoneNumber->telephoneNumberDetails->telephoneNumber);

        $this->assertEquals(ConfirmCar\AddressUsageDetails::PURPOSE_DELIVERY, $msg->deliveryAndCollection[1]->addressDeliveryCollection->addressUsageDetails->purpose);
        $this->assertEquals(ConfirmCar\AddressDetails::FORMAT_UNSTRUCTURED, $msg->deliveryAndCollection[1]->addressDeliveryCollection->addressDetails->format);
        $this->assertEquals('ROUTE DE VALBONNE', $msg->deliveryAndCollection[1]->addressDeliveryCollection->addressDetails->line1);

        $this->assertEquals('BIOT', $msg->deliveryAndCollection[1]->addressDeliveryCollection->city);
        $this->assertEquals('06410', $msg->deliveryAndCollection[1]->addressDeliveryCollection->zipCode);
        $this->assertEquals('FR', $msg->deliveryAndCollection[1]->addressDeliveryCollection->countryCode);

        $this->assertEquals(ConfirmCar\PhoneNumber::TYPE_PHONE_NUMBER, $msg->deliveryAndCollection[1]->phoneNumber->phoneOrEmailType);
        $this->assertEquals('0123456789', $msg->deliveryAndCollection[1]->phoneNumber->telephoneNumberDetails->telephoneNumber);
    }
}
