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

namespace Test\Amadeus\Client\RequestCreator;

use Amadeus\Client\Params\RequestCreatorParams;
use Amadeus\Client\RequestCreator\Base;
use Amadeus\Client\RequestOptions\Air\MultiAvailability\RequestOptions;
use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions;
use Amadeus\Client\RequestOptions\FareInformativePricingWithoutPnrOptions;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;
use Amadeus\Client\RequestOptions\Fop\ElementRef;
use Amadeus\Client\RequestOptions\Fop\Group;
use Amadeus\Client\RequestOptions\Fop\MopInfo;
use Amadeus\Client\RequestOptions\Fop\Payment;
use Amadeus\Client\RequestOptions\FopCreateFopOptions;
use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveAndDisplayOptions;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\RequestOptions\Queue;
use Amadeus\Client\RequestOptions\QueueListOptions;
use Amadeus\Client\Struct\Offer\Reference;
use Amadeus\Client\Struct\Offer\Verify;
use Amadeus\Client\Struct\Pnr\Retrieve;
use Amadeus\Client\Struct\Pnr\RetrieveAndDisplay;
use Amadeus\Client\Struct\Queue\QueueList;
use Amadeus\Client\Struct\Queue\SelectionDetails;
use Amadeus\Client\Struct\Queue\SubQueueInfoDetails;
use Test\Amadeus\BaseTestCase;

/**
 * BaseTest
 *
 * @package Amadeus\Client\RequestCreator
 */
class BaseTest extends BaseTestCase
{
    public function testUnknownMessageWillThrowRuntimeException()
    {
        $this->setExpectedException(
            '\RuntimeException',
            'No builder found for message Fare_DisplayCurrencyIATARates'
        );

        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Fare_DisplayCurrencyIATARates' => ['version' => '12.1', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $rq->createRequest(
            'Fare_DisplayCurrencyIATARates',
            $this->getMockBuilder('Amadeus\Client\RequestOptions\RequestOptionsInterface')->getMock()
        );
    }

    public function testMessageNotInWsdlWillThrowInvalidMessageException()
    {
        $this->setExpectedException(
            '\Amadeus\Client\InvalidMessageException',
            'not in WDSL'
        );

        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Fare_DisplayCurrencyIATARates' => ['version' => '12.1', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $rq->createRequest(
            'Fare_DisplayFaresForCityPair',
            $this->getMockBuilder('Amadeus\Client\RequestOptions\RequestOptionsInterface')->getMock()
        );
    }

    public function testCanCreatePnrRetrieveMessage()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['PNR_Retrieve' => ['version' => '14.2', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'PNR_Retrieve',
            new PnrRetrieveOptions(['recordLocator' => 'ABC123'])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Retrieve::RETR_TYPE_BY_RECLOC, $message->retrievalFacts->retrieve->type);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\ReservationOrProfileIdentifier', $message->retrievalFacts->reservationOrProfileIdentifier);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Reservation', $message->retrievalFacts->reservationOrProfileIdentifier->reservation);
        $this->assertEquals('ABC123', $message->retrievalFacts->reservationOrProfileIdentifier->reservation->controlNumber);

        $this->assertNull($message->settings);
    }
    
    public function testCanCreatePnrRetrieveMessageInContext()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['PNR_Retrieve' => ['version' => '14.2', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'PNR_Retrieve',
            new PnrRetrieveOptions()
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve', $message);
        /** @var Retrieve $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\RetrievalFacts', $message->retrievalFacts);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Retrieve\Retrieve', $message->retrievalFacts->retrieve);
        $this->assertEquals(Retrieve::RETR_TYPE_ACTIVE_PNR, $message->retrievalFacts->retrieve->type);
        $this->assertNull($message->retrievalFacts->reservationOrProfileIdentifier);

        $this->assertNull($message->settings);
    }

    public function testCanCreatePnrRetrieveAndDisplayMessage()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['PNR_RetrieveAndDisplay' => ['version' => '12.1', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'PNR_RetrieveAndDisplay',
            new PnrRetrieveAndDisplayOptions(['recordLocator' => 'ABC123', 'retrieveOption' => 'OFR'])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\RetrieveAndDisplay', $message);
        /** @var RetrieveAndDisplay $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ReservationInfo', $message->reservationInfo);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\Reservation', $message->reservationInfo->reservation);
        $this->assertEquals('ABC123', $message->reservationInfo->reservation->controlNumber);
        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\RetrieveAndDisplay\DynamicOutputOption', $message->dynamicOutputOption);
    }

    public function testCanCreateOfferVerifyMessage()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Offer_VerifyOffer' => ['version' => '10.1', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'Offer_VerifyOffer',
            new OfferVerifyOptions(['offerReference' => 1, 'segmentName' => 'AIR'])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Offer\Verify', $message);
        /** @var Verify $message */
        $this->assertInstanceOf('Amadeus\Client\Struct\Offer\OfferTattoo', $message->offerTatoo);
        $this->assertEquals('AIR', $message->offerTatoo->segmentName);
        $this->assertInstanceOf('Amadeus\Client\Struct\Offer\Reference', $message->offerTatoo->reference);
        $this->assertEquals(Reference::TYPE_OFFER_TATTOO, $message->offerTatoo->reference->type);
        $this->assertEquals(1, $message->offerTatoo->reference->value);
    }

    public function testCanCreateQueueListMessage()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Queue_List' => ['version' => '11.1', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'Queue_List',
            new QueueListOptions([
                'queue' => new Queue([
                    'queue' => 50,
                    'category' => 1
                ])
            ])
        );

        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\QueueList', $message);
        /** @var QueueList $message */
        $this->assertNull($message->date);
        $this->assertNull($message->scanRange);
        $this->assertNull($message->scroll);
        $this->assertEmpty($message->searchCriteria);
        $this->assertNull($message->targetOffice);

        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\QueueNumber', $message->queueNumber);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\QueueDetails', $message->queueNumber->queueDetails);
        $this->assertEquals(50, $message->queueNumber->queueDetails->number);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\CategoryDetails', $message->categoryDetails);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\SubQueueInfoDetails', $message->categoryDetails->subQueueInfoDetails);
        $this->assertEquals(1, $message->categoryDetails->subQueueInfoDetails->itemNumber);
        $this->assertEquals(SubQueueInfoDetails::IDTYPE_CATEGORY, $message->categoryDetails->subQueueInfoDetails->identificationType);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\SortCriteria', $message->sortCriteria);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\Dumbo', $message->sortCriteria->dumbo);
        $this->assertInternalType('array', $message->sortCriteria->sortOption);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\SortOption', $message->sortCriteria->sortOption[0]);
        $this->assertInstanceOf('Amadeus\Client\Struct\Queue\SelectionDetails', $message->sortCriteria->sortOption[0]->selectionDetails);
        $this->assertEquals(SelectionDetails::LIST_OPTION_SORT_CREATION, $message->sortCriteria->sortOption[0]->selectionDetails->option);
    }

    public function testCanCreateFareInformativePricingMessageV12()
    {
        $this->setExpectedException('Amadeus\Client\RequestCreator\MessageVersionUnsupportedException');

        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Fare_InformativePricingWithoutPNR' => ['version' => '12.3', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $rq->createRequest(
            'Fare_InformativePricingWithoutPNR',
            new FareInformativePricingWithoutPnrOptions([

            ])
        );
    }

    public function testCanCreateFareInformativeBestPricingMessageV12()
    {
        $this->setExpectedException('Amadeus\Client\RequestCreator\MessageVersionUnsupportedException');

        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Fare_InformativeBestPricingWithoutPNR' => ['version' => '12.3', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'Fare_InformativeBestPricingWithoutPNR',
            new FareInformativeBestPricingWithoutPnrOptions([

            ])
        );

        $this->assertInstanceOf('\Amadeus\Client\Struct\Fare\InformativePricingWithoutPNR12', $message);
    }

    /**
     * Attempting to reproduce https://github.com/amabnl/amadeus-ws-client/issues/57
     */
    public function testCanCreateFarePricePnrWithBookingClass73()
    {

        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Fare_PricePNRWithBookingClass' => ['version' => '07.3', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'Fare_PricePNRWithBookingClass',
            new FarePricePnrWithBookingClassOptions([
                'overrideOptions' => [
                    FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_PUB,
                    FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_CORPUNI,
                    'DO',
                ],
                'pricingsFareBasis' => [
                    new FareBasis([
                        'fareBasisCode' => 'CTRIPN2',
                        'references' => [
                            new PaxSegRef([
                                'reference' => 1,
                                'type' => PaxSegRef::TYPE_SEGMENT
                            ]),
                            new PaxSegRef([
                                'reference' => 1,
                                'type' => PaxSegRef::TYPE_PASSENGER
                            ])
                        ]
                    ])
                ]
            ])
        );

        $this->assertInstanceOf('\Amadeus\Client\Struct\Fare\PricePNRWithBookingClass12', $message);
    }

    public function testCanCreateAirMultiAvailability16()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['Air_MultiAvailability' => ['version' => '16.1', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'Air_MultiAvailability',
            new AirMultiAvailabilityOptions([
                'actionCode' => AirMultiAvailabilityOptions::ACTION_SCHEDULE,
                'requestOptions' => [
                    new RequestOptions([
                        'departureDate' => \DateTime::createFromFormat('Ymd-His', '20170215-140000', new \DateTimeZone('UTC')),
                        'from' => 'NCE',
                        'to' => 'NYC',
                        'cabinCode' => RequestOptions::CABIN_ECONOMY_PREMIUM_MAIN,
                        'includedConnections' => ['PAR'],
                        'nrOfSeats' => 5,
                        'includedAirlines' => ['AF'],
                        'requestType' => RequestOptions::REQ_TYPE_BY_ARRIVAL_TIME
                    ])
                ]
            ])
        );

        $this->assertInstanceOf('\Amadeus\Client\Struct\Air\MultiAvailability16', $message);
    }

    public function testCanCreateFopCreateFormOfPayment14()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => ['FOP_CreateFormOfPayment' => ['version' => '11.1', 'wsdl' => 'aabbccdd']]
        ]);

        $rq = new Base($par);

        $message = $rq->createRequest(
            'FOP_CreateFormOfPayment',
            new FopCreateFopOptions([
                'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
                'fopGroup' => [
                    new Group([
                        'elementRef' => [
                            new ElementRef([
                                'type' => ElementRef::TYPE_TST_NUMBER,
                                'value' => 1
                            ])
                        ],
                        'mopInfo' => [
                            new MopInfo([
                                'sequenceNr' => 0,
                                'fopCode' => 'CCCA',
                                'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                                'payMerchant' => 'BA',
                                'payments' => [
                                    new Payment([
                                        'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                        'amount' => 300,
                                        'currency' => "EUR"
                                    ])
                                ],
                                'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                                'creditCardInfo' => new CreditCardInfo([
                                    'vendorCode' => 'CA',
                                    'cardNumber' => '5000000000000009',
                                    'expiryDate' => '1014',
                                    'securityId' => 123,
                                    'approvalCode' => '123456',
                                    'sourceOfApproval' => CreditCardInfo::APPROVAL_SOURCE_MANUAL,
                                    'name' => 'dummy name'
                                ])
                            ])
                        ]
                    ])
                ]
            ])
        );

        $this->assertInstanceOf('\Amadeus\Client\Struct\Fop\CreateFormOfPayment14', $message);

        $this->assertInstanceOf('\Amadeus\Client\Struct\Fop\CreateFormOfPayment\GroupUsage14', $message->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage);
        $this->assertInstanceOf('\Amadeus\Client\Struct\Fop\AttributeDetails', $message->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails);
        $this->assertInternalType('string', $message->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails->attributeType);
    }

    public function testCanTryBuildingSameMessageTwiceWillReuseBuilder()
    {
        $par = new RequestCreatorParams([
            'originatorOfficeId' => 'BRUXXXXXX',
            'receivedFrom' => 'some RF string',
            'messagesAndVersions' => [
                'Fare_InformativeBestPricingWithoutPNR' => [
                    'version' => '14.1',
                    'wsdl' => 'dc22e4ee'
                ]
            ]
        ]);

        $rq = new Base($par);

        $rq->createRequest(
            'Fare_InformativeBestPricingWithoutPNR',
            new FareInformativeBestPricingWithoutPnrOptions([

            ])
        );

        $rq->createRequest(
            'Fare_InformativeBestPricingWithoutPNR',
            new FareInformativeBestPricingWithoutPnrOptions([
                'pricingOptions' => new PricingOptions([
                    'overrideOptions' => [
                        PricingOptions::OVERRIDE_FARETYPE_PUB,
                        PricingOptions::OVERRIDE_FARETYPE_NEG,
                        PricingOptions::OVERRIDE_FARETYPE_UNI
                    ]
                ])
            ])
        );
    }
}
