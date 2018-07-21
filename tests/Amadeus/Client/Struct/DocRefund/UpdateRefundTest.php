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

namespace Test\Amadeus\Client\Struct\DocRefund;

use Amadeus\Client\RequestOptions\DocRefund\AddressOpt;
use Amadeus\Client\RequestOptions\DocRefund\CommissionOpt;
use Amadeus\Client\RequestOptions\DocRefund\FopOpt;
use Amadeus\Client\RequestOptions\DocRefund\FreeTextOpt;
use Amadeus\Client\RequestOptions\DocRefund\MonetaryData;
use Amadeus\Client\RequestOptions\DocRefund\Reference;
use Amadeus\Client\RequestOptions\DocRefund\RefundItinOpt;
use Amadeus\Client\RequestOptions\DocRefund\TaxData;
use Amadeus\Client\RequestOptions\DocRefund\Ticket;
use Amadeus\Client\RequestOptions\DocRefund\TickGroupOpt;
use Amadeus\Client\RequestOptions\DocRefundUpdateRefundOptions;
use Amadeus\Client\Struct\DocRefund\UpdateRefund;
use Amadeus\Client\Struct\Fop\FormOfPayment;
use Test\Amadeus\BaseTestCase;

/**
 * UpdateRefundTest
 *
 * @package Test\Amadeus\Client\Struct\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class UpdateRefundTest extends BaseTestCase
{
    /**
     * 5.1 Operation: Conjunction ticket
     */
    public function testCanMakeMessageConjunctionTicket()
    {
        $opt = new DocRefundUpdateRefundOptions([
            'originator' => '0001AA',
            'originatorId' => '23491193',
            'refundDate' => \DateTime::createFromFormat('Ymd', '20031125'),
            'ticketedDate' => \DateTime::createFromFormat('Ymd', '20030522'),
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_TKT_INDICATOR,
                    'value' => 'Y'
                ]),
                new Reference([
                    'type' => Reference::TYPE_DATA_SOURCE,
                    'value' => 'F'
                ])
            ],
            'tickets' => [
                new Ticket([
                    'number' => '22021541124593',
                    'ticketGroup' => [
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_1,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_2,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_3,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_4,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ])
                    ]
                ]),
                new Ticket([
                    'number' => '22021541124604',
                    'ticketGroup' => [
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_1,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_2,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH07A'
                        ])
                    ]
                ])
            ],
            'travellerPrioDateOfJoining' => \DateTime::createFromFormat('Ymd', '20070101'),
            'travellerPrioReference' => '0077701F',
            'monetaryData' => [
                new MonetaryData([
                    'type' => MonetaryData::TYPE_BASE_FARE,
                    'amount' => 401.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_USED,
                    'amount' => 0.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_REFUND,
                    'amount' => 401.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_REFUND_TOTAL,
                    'amount' => 457.74,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_TOTAL_TAXES,
                    'amount' => 56.74,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => 'TP',
                    'amount' => 56.74,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => 'OBP',
                    'amount' => 0.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => 'TGV',
                    'amount' => 374.93,
                    'currency' => 'EUR'
                ])
            ],
            'taxData' => [
                new TaxData([
                    'category' => 'H',
                    'rate' => 16.14,
                    'currencyCode' => 'EUR',
                    'type' => 'DE'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 3.45,
                    'currencyCode' => 'EUR',
                    'type' => 'YC'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 9.67,
                    'currencyCode' => 'EUR',
                    'type' => 'US'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 9.67,
                    'currencyCode' => 'EUR',
                    'type' => 'US'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 3.14,
                    'currencyCode' => 'EUR',
                    'type' => 'XA'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 4.39,
                    'currencyCode' => 'EUR',
                    'type' => 'XY'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 6.28,
                    'currencyCode' => 'EUR',
                    'type' => 'AY'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 4.00,
                    'currencyCode' => 'EUR',
                    'type' => 'DU'
                ]),
                new TaxData([
                    'category' => '701',
                    'rate' => 56.74,
                    'currencyCode' => 'EUR',
                    'type' => TaxData::TYPE_EXTENDED_TAXES
                ])
            ],
            'formOfPayment' => [
                new FopOpt([
                    'fopType' => FopOpt::TYPE_MISCELLANEOUS,
                    'fopAmount' => 457.74,
                    'freeText' => [
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => '##0##'
                        ]),
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => 'IDBANK'
                        ])
                    ]
                ])
            ],
            'refundedRouteStations' => [
                'FRA',
                'MUC',
                'JFK',
                'BKK',
                'FRA'
            ]
        ]);

        $msg = new UpdateRefund($opt);

        $this->assertNull($msg->ticketNumber);
        $this->assertNull($msg->structuredAddress);
        $this->assertEmpty($msg->refundedItinerary);
        $this->assertNull($msg->tourInformation);
        $this->assertNull($msg->commission);
        $this->assertNull($msg->pricingDetails);
        $this->assertNull($msg->travellerInformation);
        $this->assertEmpty($msg->interactiveFreeText);

        $this->assertEquals('0001AA', $msg->userIdentification->originator);
        $this->assertEquals('23491193', $msg->userIdentification->originIdentification->originatorId);

        $this->assertCount(2, $msg->dateTimeInformation);
        $this->assertEquals(UpdateRefund\DateTimeInformation::OPT_DATE_OF_REFUND, $msg->dateTimeInformation[0]->businessSemantic);
        $this->assertEquals('25', $msg->dateTimeInformation[0]->dateTime->day);
        $this->assertEquals('11', $msg->dateTimeInformation[0]->dateTime->month);
        $this->assertEquals('2003', $msg->dateTimeInformation[0]->dateTime->year);

        $this->assertEquals(UpdateRefund\DateTimeInformation::OPT_DATE_TICKETED, $msg->dateTimeInformation[1]->businessSemantic);
        $this->assertEquals('22', $msg->dateTimeInformation[1]->dateTime->day);
        $this->assertEquals('5', $msg->dateTimeInformation[1]->dateTime->month);
        $this->assertEquals('2003', $msg->dateTimeInformation[1]->dateTime->year);

        $this->assertCount(2, $msg->referenceInformation->referenceDetails);
        $this->assertEquals('Y', $msg->referenceInformation->referenceDetails[0]->value);
        $this->assertEquals(UpdateRefund\ReferenceDetails::TYPE_TKT_INDICATOR, $msg->referenceInformation->referenceDetails[0]->type);
        $this->assertEquals('F', $msg->referenceInformation->referenceDetails[1]->value);
        $this->assertEquals(UpdateRefund\ReferenceDetails::TYPE_DATA_SOURCE, $msg->referenceInformation->referenceDetails[1]->type);

        $this->assertCount(2, $msg->ticket);

        $this->assertEquals('22021541124593', $msg->ticket[0]->ticketInformation->documentDetails->number);
        $this->assertNull($msg->ticket[0]->ticketInformation->documentDetails->type);
        $this->assertCount(4, $msg->ticket[0]->ticketGroup);
        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_1, $msg->ticket[0]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[0]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertEquals('LH07A', $msg->ticket[0]->ticketGroup[0]->boardingPriority->priorityDetails->description);
        $this->assertNull($msg->ticket[0]->ticketGroup[0]->referenceInformation);
        $this->assertNull($msg->ticket[0]->ticketGroup[0]->actionIdentification);

        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_2, $msg->ticket[0]->ticketGroup[1]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[0]->ticketGroup[1]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertEquals('LH07A', $msg->ticket[0]->ticketGroup[1]->boardingPriority->priorityDetails->description);
        $this->assertNull($msg->ticket[0]->ticketGroup[1]->referenceInformation);
        $this->assertNull($msg->ticket[0]->ticketGroup[1]->actionIdentification);

        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_3, $msg->ticket[0]->ticketGroup[2]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[0]->ticketGroup[2]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertEquals('LH07A', $msg->ticket[0]->ticketGroup[2]->boardingPriority->priorityDetails->description);
        $this->assertNull($msg->ticket[0]->ticketGroup[2]->referenceInformation);
        $this->assertNull($msg->ticket[0]->ticketGroup[2]->actionIdentification);

        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_4, $msg->ticket[0]->ticketGroup[3]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[0]->ticketGroup[3]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertEquals('LH07A', $msg->ticket[0]->ticketGroup[3]->boardingPriority->priorityDetails->description);
        $this->assertNull($msg->ticket[0]->ticketGroup[3]->referenceInformation);
        $this->assertNull($msg->ticket[0]->ticketGroup[3]->actionIdentification);

        $this->assertEquals('22021541124604', $msg->ticket[1]->ticketInformation->documentDetails->number);
        $this->assertNull($msg->ticket[1]->ticketInformation->documentDetails->type);
        $this->assertCount(2, $msg->ticket[1]->ticketGroup);
        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_1, $msg->ticket[1]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[1]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertEquals('LH07A', $msg->ticket[1]->ticketGroup[0]->boardingPriority->priorityDetails->description);
        $this->assertNull($msg->ticket[1]->ticketGroup[0]->referenceInformation);
        $this->assertNull($msg->ticket[1]->ticketGroup[0]->actionIdentification);

        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_2, $msg->ticket[1]->ticketGroup[1]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[1]->ticketGroup[1]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertEquals('LH07A', $msg->ticket[1]->ticketGroup[1]->boardingPriority->priorityDetails->description);
        $this->assertNull($msg->ticket[1]->ticketGroup[1]->referenceInformation);
        $this->assertNull($msg->ticket[1]->ticketGroup[1]->actionIdentification);

        $this->assertEquals('01JAN07', $msg->travellerPriorityInfo->dateOfJoining);
        $this->assertEquals('0077701F', $msg->travellerPriorityInfo->travellerReference);
        $this->assertNull($msg->travellerPriorityInfo->company);

        $this->assertEquals(UpdateRefund\MonetaryDetails::TYPE_BASE_FARE, $msg->monetaryInformation->monetaryDetails->typeQualifier);
        $this->assertEquals(401.00, $msg->monetaryInformation->monetaryDetails->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->monetaryDetails->currency);

        $this->assertCount(7, $msg->monetaryInformation->otherMonetaryDetails);

        $this->assertEquals('RFU', $msg->monetaryInformation->otherMonetaryDetails[0]->typeQualifier);
        $this->assertEquals(0.00, $msg->monetaryInformation->otherMonetaryDetails[0]->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->otherMonetaryDetails[0]->currency);
        $this->assertEquals('FRF', $msg->monetaryInformation->otherMonetaryDetails[1]->typeQualifier);
        $this->assertEquals(401.00, $msg->monetaryInformation->otherMonetaryDetails[1]->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->otherMonetaryDetails[1]->currency);
        $this->assertEquals('RFT', $msg->monetaryInformation->otherMonetaryDetails[2]->typeQualifier);
        $this->assertEquals(457.74, $msg->monetaryInformation->otherMonetaryDetails[2]->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->otherMonetaryDetails[2]->currency);
        $this->assertEquals('TXT', $msg->monetaryInformation->otherMonetaryDetails[3]->typeQualifier);
        $this->assertEquals(56.74, $msg->monetaryInformation->otherMonetaryDetails[3]->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->otherMonetaryDetails[3]->currency);
        $this->assertEquals('TP', $msg->monetaryInformation->otherMonetaryDetails[4]->typeQualifier);
        $this->assertEquals(56.74, $msg->monetaryInformation->otherMonetaryDetails[4]->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->otherMonetaryDetails[4]->currency);
        $this->assertEquals('OBP', $msg->monetaryInformation->otherMonetaryDetails[5]->typeQualifier);
        $this->assertEquals(0.00, $msg->monetaryInformation->otherMonetaryDetails[5]->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->otherMonetaryDetails[5]->currency);
        $this->assertEquals('TGV', $msg->monetaryInformation->otherMonetaryDetails[6]->typeQualifier);
        $this->assertEquals(374.93, $msg->monetaryInformation->otherMonetaryDetails[6]->amount);
        $this->assertEquals('EUR', $msg->monetaryInformation->otherMonetaryDetails[6]->currency);

        $this->assertCount(9, $msg->taxDetailsInformation);

        $this->assertEquals('H', $msg->taxDetailsInformation[0]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[0]->taxDetails);
        $this->assertEquals('DE', $msg->taxDetailsInformation[0]->taxDetails[0]->type);
        $this->assertEquals('16.14', $msg->taxDetailsInformation[0]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[0]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[0]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[1]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[1]->taxDetails);
        $this->assertEquals('YC', $msg->taxDetailsInformation[1]->taxDetails[0]->type);
        $this->assertEquals(3.45, $msg->taxDetailsInformation[1]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[1]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[1]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[2]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[2]->taxDetails);
        $this->assertEquals('US', $msg->taxDetailsInformation[2]->taxDetails[0]->type);
        $this->assertEquals(9.67, $msg->taxDetailsInformation[2]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[2]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[2]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[3]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[3]->taxDetails);
        $this->assertEquals('US', $msg->taxDetailsInformation[3]->taxDetails[0]->type);
        $this->assertEquals(9.67, $msg->taxDetailsInformation[3]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[3]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[3]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[4]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[4]->taxDetails);
        $this->assertEquals('XA', $msg->taxDetailsInformation[4]->taxDetails[0]->type);
        $this->assertEquals(3.14, $msg->taxDetailsInformation[4]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[4]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[4]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[5]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[5]->taxDetails);
        $this->assertEquals('XY', $msg->taxDetailsInformation[5]->taxDetails[0]->type);
        $this->assertEquals(4.39, $msg->taxDetailsInformation[5]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[5]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[5]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[6]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[6]->taxDetails);
        $this->assertEquals('AY', $msg->taxDetailsInformation[6]->taxDetails[0]->type);
        $this->assertEquals(6.28, $msg->taxDetailsInformation[6]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[6]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[6]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[7]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[7]->taxDetails);
        $this->assertEquals('DU', $msg->taxDetailsInformation[7]->taxDetails[0]->type);
        $this->assertEquals(4.00, $msg->taxDetailsInformation[7]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[7]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[7]->taxDetails[0]->countryCode);

        $this->assertEquals('701', $msg->taxDetailsInformation[8]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[8]->taxDetails);
        $this->assertEquals(UpdateRefund\TaxDetails::TYPE_EXTENDED_TAXES, $msg->taxDetailsInformation[8]->taxDetails[0]->type);
        $this->assertEquals(56.74, $msg->taxDetailsInformation[8]->taxDetails[0]->rate);
        $this->assertEquals('EUR', $msg->taxDetailsInformation[8]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[8]->taxDetails[0]->countryCode);

        $this->assertCount(1, $msg->fopGroup);
        $this->assertEquals(FormOfPayment::TYPE_MISCELLANEOUS, $msg->fopGroup[0]->formOfPaymentInformation->formOfPayment->type);
        $this->assertEquals(457.74, $msg->fopGroup[0]->formOfPaymentInformation->formOfPayment->amount);
        $this->assertNull($msg->fopGroup[0]->formOfPaymentInformation->formOfPayment->authorisedAmount);
        $this->assertNull($msg->fopGroup[0]->formOfPaymentInformation->formOfPayment->sourceOfApproval);
        $this->assertCount(2, $msg->fopGroup[0]->interactiveFreeText);
        $this->assertEquals('##0##', $msg->fopGroup[0]->interactiveFreeText[0]->freeText);
        $this->assertEquals('CFP', $msg->fopGroup[0]->interactiveFreeText[0]->freeTextQualification->informationType);
        $this->assertEquals(UpdateRefund\FreeTextQualification::QUAL_CODED_AND_LITERAL_TEXT, $msg->fopGroup[0]->interactiveFreeText[0]->freeTextQualification->textSubjectQualifier);
        $this->assertEquals('IDBANK', $msg->fopGroup[0]->interactiveFreeText[1]->freeText);
        $this->assertEquals('CFP', $msg->fopGroup[0]->interactiveFreeText[1]->freeTextQualification->informationType);
        $this->assertEquals(UpdateRefund\FreeTextQualification::QUAL_CODED_AND_LITERAL_TEXT, $msg->fopGroup[0]->interactiveFreeText[1]->freeTextQualification->textSubjectQualifier);

        $this->assertCount(5, $msg->refundedRoute->routingDetails);
        $this->assertEquals('FRA', $msg->refundedRoute->routingDetails[0]->station);
        $this->assertEquals('MUC', $msg->refundedRoute->routingDetails[1]->station);
        $this->assertEquals('JFK', $msg->refundedRoute->routingDetails[2]->station);
        $this->assertEquals('BKK', $msg->refundedRoute->routingDetails[3]->station);
        $this->assertEquals('FRA', $msg->refundedRoute->routingDetails[4]->station);
    }

    /**
     * 5.2 Operation: Currency conversion
     */
    public function testCanMakeMessageWithCurrencyConversion()
    {
        $opt = new DocRefundUpdateRefundOptions([
            'originator' => '0001AA',
            'originatorId' => '92490300',
            'refundDate' => \DateTime::createFromFormat('Ymd', '20031125'),
            'ticketedDate' => \DateTime::createFromFormat('Ymd', '20030522'),
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_TKT_INDICATOR,
                    'value' => 'Y'
                ]),
                new Reference([
                    'type' => Reference::TYPE_DATA_SOURCE,
                    'value' => 'F'
                ])
            ],
            'passengerSurName' => 'AMADEUSTEST JAN MR',
            'tickets' => [
                new Ticket([
                    'number' => '22021542207795',
                    'ticketGroup' => [
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_1,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                            'boardingPriority' => 'LH05A',
                            'actionRequestCode' => 'STF',
                            'references' => ['12345678901234567890']
                        ])
                    ]
                ])
            ],
            'monetaryData' => [
                new MonetaryData([
                    'type' => MonetaryData::TYPE_BASE_FARE,
                    'amount' => 10660,
                    'currency' => 'RUB'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_CURENCY_CONVERSION_AMOUNT,
                    'amount' => 0.027094,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_USED,
                    'amount' => 0,
                    'currency' => 'RUB'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_REFUND,
                    'amount' => 10660,
                    'currency' => 'RUB'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_REFUND_TOTAL,
                    'amount' => 13020,
                    'currency' => 'RUB'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_TOTAL_TAXES,
                    'amount' => 2360,
                    'currency' => 'RUB'
                ]),
                new MonetaryData([
                    'type' => 'TP',
                    'amount' => 2360,
                    'currency' => 'RUB'
                ]),
                new MonetaryData([
                    'type' => 'OBP',
                    'amount' => 555,
                    'currency' => 'RUB'
                ])
            ],
            'taxData' => [
                new TaxData([
                    'category' => 'H',
                    'rate' => 1295.00,
                    'currencyCode' => 'RUB',
                    'type' => 'YQ'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 776.00,
                    'currencyCode' => 'RUB',
                    'type' => 'RD'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 289.00,
                    'currencyCode' => 'RUB',
                    'type' => 'DE'
                ]),
                new TaxData([
                    'category' => '701',
                    'rate' => 2360,
                    'currencyCode' => 'RUB',
                    'type' => TaxData::TYPE_EXTENDED_TAXES
                ])
            ],
            'formOfPayment' => [
                new FopOpt([
                    'fopType' => FopOpt::TYPE_CASH,
                    'fopAmount' => 13020,
                    'freeText' => [
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => '##0##'
                        ]),
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => 'CASH'
                        ])
                    ]
                ])
            ],
            'refundedRouteStations' => [
                'FRA',
                'HAM'
            ],
            'address' => new AddressOpt([
                'company' => 'GREAT COMPANY',
                'name' => 'MR SMITH',
                'addressLine1' => '12 LONG STREET',
                'postalCode' => 'BS7890',
                'city' => 'NICE',
                'country' => 'FR'
            ])
        ]);

        $msg = new UpdateRefund($opt);

        $this->assertNull($msg->ticketNumber);
        $this->assertEmpty($msg->refundedItinerary);
        $this->assertNull($msg->tourInformation);
        $this->assertNull($msg->commission);
        $this->assertNull($msg->pricingDetails);
        $this->assertEmpty($msg->interactiveFreeText);

        $this->assertEquals('0001AA', $msg->userIdentification->originator);
        $this->assertEquals('92490300', $msg->userIdentification->originIdentification->originatorId);

        $this->assertCount(2, $msg->dateTimeInformation);
        $this->assertCount(2, $msg->referenceInformation->referenceDetails);

        $this->assertCount(1, $msg->ticket);
        $this->assertEquals('22021542207795', $msg->ticket[0]->ticketInformation->documentDetails->number);
        $this->assertNull($msg->ticket[0]->ticketInformation->documentDetails->type);
        $this->assertCount(1, $msg->ticket[0]->ticketGroup);
        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_1, $msg->ticket[0]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[0]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertEquals('LH05A', $msg->ticket[0]->ticketGroup[0]->boardingPriority->priorityDetails->description);
        $this->assertCount(1, $msg->ticket[0]->ticketGroup[0]->referenceInformation->referenceDetails);
        $this->assertEquals(UpdateRefund\ReferenceDetails::TYPE_VALIDATION_CERTIFICATE_USED_FOR_STAFF, $msg->ticket[0]->ticketGroup[0]->referenceInformation->referenceDetails[0]->type);
        $this->assertEquals('12345678901234567890', $msg->ticket[0]->ticketGroup[0]->referenceInformation->referenceDetails[0]->value);
        $this->assertEquals('STF', $msg->ticket[0]->ticketGroup[0]->actionIdentification->actionRequestCode);
        $this->assertNull($msg->ticket[0]->ticketGroup[0]->actionIdentification->productDetails);


        $this->assertEquals(UpdateRefund\MonetaryDetails::TYPE_BASE_FARE, $msg->monetaryInformation->monetaryDetails->typeQualifier);
        $this->assertEquals(10660, $msg->monetaryInformation->monetaryDetails->amount);
        $this->assertEquals('RUB', $msg->monetaryInformation->monetaryDetails->currency);

        $this->assertCount(7, $msg->monetaryInformation->otherMonetaryDetails);
        //Ehh, i don't feel like asserting them again, effort

        $this->assertCount(4, $msg->taxDetailsInformation);
        $this->assertEquals('H', $msg->taxDetailsInformation[0]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[0]->taxDetails);
        $this->assertEquals('YQ', $msg->taxDetailsInformation[0]->taxDetails[0]->type);
        $this->assertEquals(1295, $msg->taxDetailsInformation[0]->taxDetails[0]->rate);
        $this->assertEquals('RUB', $msg->taxDetailsInformation[0]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[0]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[1]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[1]->taxDetails);
        $this->assertEquals('RD', $msg->taxDetailsInformation[1]->taxDetails[0]->type);
        $this->assertEquals(776, $msg->taxDetailsInformation[1]->taxDetails[0]->rate);
        $this->assertEquals('RUB', $msg->taxDetailsInformation[1]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[1]->taxDetails[0]->countryCode);

        $this->assertEquals('H', $msg->taxDetailsInformation[2]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[2]->taxDetails);
        $this->assertEquals('DE', $msg->taxDetailsInformation[2]->taxDetails[0]->type);
        $this->assertEquals(289, $msg->taxDetailsInformation[2]->taxDetails[0]->rate);
        $this->assertEquals('RUB', $msg->taxDetailsInformation[2]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[2]->taxDetails[0]->countryCode);

        $this->assertEquals('701', $msg->taxDetailsInformation[3]->taxCategory);
        $this->assertCount(1, $msg->taxDetailsInformation[3]->taxDetails);
        $this->assertEquals(UpdateRefund\TaxDetails::TYPE_EXTENDED_TAXES, $msg->taxDetailsInformation[3]->taxDetails[0]->type);
        $this->assertEquals(2360, $msg->taxDetailsInformation[3]->taxDetails[0]->rate);
        $this->assertEquals('RUB', $msg->taxDetailsInformation[3]->taxDetails[0]->currencyCode);
        $this->assertNull($msg->taxDetailsInformation[3]->taxDetails[0]->countryCode);

        $this->assertEquals(UpdateRefund\StructuredAddress::TYPE_BILLING_ADDRESS, $msg->structuredAddress->informationType);
        $this->assertCount(6, $msg->structuredAddress->address);
        $this->assertEquals(UpdateRefund\Address::OPTION_COMPANY, $msg->structuredAddress->address[0]->option);
        $this->assertEquals('GREAT COMPANY', $msg->structuredAddress->address[0]->optionText);
        $this->assertEquals(UpdateRefund\Address::OPTION_NAME, $msg->structuredAddress->address[1]->option);
        $this->assertEquals('MR SMITH', $msg->structuredAddress->address[1]->optionText);
        $this->assertEquals(UpdateRefund\Address::OPTION_ADDRESS_LINE_1, $msg->structuredAddress->address[2]->option);
        $this->assertEquals('12 LONG STREET', $msg->structuredAddress->address[2]->optionText);
        $this->assertEquals(UpdateRefund\Address::OPTION_CITY, $msg->structuredAddress->address[3]->option);
        $this->assertEquals('NICE', $msg->structuredAddress->address[3]->optionText);
        $this->assertEquals(UpdateRefund\Address::OPTION_COUNTRY, $msg->structuredAddress->address[4]->option);
        $this->assertEquals('FR', $msg->structuredAddress->address[4]->optionText);
        $this->assertEquals(UpdateRefund\Address::OPTION_POSTAL_CODE, $msg->structuredAddress->address[5]->option);
        $this->assertEquals('BS7890', $msg->structuredAddress->address[5]->optionText);

        $this->assertEquals('AMADEUSTEST JAN MR', $msg->travellerInformation->paxDetails->surname);
        $this->assertNull($msg->travellerInformation->paxDetails->type);
        $this->assertNull($msg->travellerInformation->paxDetails->quantity);
        $this->assertNull($msg->travellerInformation->paxDetails->status);
    }

    public function testCanMakeMessageNoShowFeeUpdate()
    {
        $opt = new DocRefundUpdateRefundOptions([
            'originator' => '0001AA',
            'originatorId' => '28055300',
            'refundDate' => \DateTime::createFromFormat('Ymd', '20111116'),
            'ticketedDate' => \DateTime::createFromFormat('Ymd', '20111115'),
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_TKT_INDICATOR,
                    'value' => 'Y'
                ]),
                new Reference([
                    'type' => Reference::TYPE_DATA_SOURCE,
                    'value' => 'F'
                ])
            ],
            'passengerSurName' => 'NOSHOW/REFUND',
            'tickets' => [
                new Ticket([
                    'number' => '17246548165754',
                    'ticketGroup' => [
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_1,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                        ]),
                        new TickGroupOpt([
                            'couponNumber' => TickGroupOpt::COUPON_2,
                            'couponStatus' => TickGroupOpt::STATUS_REFUNDED,
                        ]),
                    ]
                ])
            ],
            'monetaryData' => [
                new MonetaryData([
                    'type' => MonetaryData::TYPE_BASE_FARE,
                    'amount' => 54.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_USED,
                    'amount' => 0,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_FARE_REFUND,
                    'amount' => 54.00,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_REFUND_TOTAL,
                    'amount' => 138.53,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_TOTAL_TAXES,
                    'amount' => 84.53,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => 'TP',
                    'amount' => 84.53,
                    'currency' => 'EUR'
                ]),
                new MonetaryData([
                    'type' => MonetaryData::TYPE_NO_SHOW_FEE,
                    'amount' => 20,
                    'currency' => 'EUR'
                ])
            ],
            'pricingTicketIndicator' => DocRefundUpdateRefundOptions::PRICING_IND_INTERNATIONAL_ITINERARY,
            'commission' => [
                new CommissionOpt([
                    'type' => CommissionOpt::TYPE_NEW_COMMISSION,
                    'amount' => 0.00,
                    'freeText' => 'P',
                    'rate' => 0.00,
                ])
            ],
            'taxData' => [
                new TaxData([
                    'category' => 'H',
                    'rate' => 2.00,
                    'currencyCode' => 'EUR',
                    'type' => 'YQ'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 17.86,
                    'currencyCode' => 'EUR',
                    'type' => 'OY'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 50.75,
                    'currencyCode' => 'EUR',
                    'type' => 'RD'
                ]),
                new TaxData([
                    'category' => 'H',
                    'rate' => 13.92,
                    'currencyCode' => 'EUR',
                    'type' => 'DE'
                ])
            ],
            'formOfPayment' => [
                new FopOpt([
                    'fopType' => FopOpt::TYPE_CASH,
                    'fopAmount' => 138.53,
                    'freeText' => [
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => '##0##'
                        ]),
                        new FreeTextOpt([
                            'type' => 'CFP',
                            'freeText' => 'CASH'
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new UpdateRefund($opt);

        $this->assertNull($msg->ticketNumber);
        $this->assertEmpty($msg->refundedItinerary);
        $this->assertNull($msg->tourInformation);

        $this->assertEquals('0001AA', $msg->userIdentification->originator);
        $this->assertEquals('28055300', $msg->userIdentification->originIdentification->originatorId);

        $this->assertCount(2, $msg->dateTimeInformation);
        $this->assertEquals(UpdateRefund\DateTimeInformation::OPT_DATE_OF_REFUND, $msg->dateTimeInformation[0]->businessSemantic);
        $this->assertEquals('16', $msg->dateTimeInformation[0]->dateTime->day);
        $this->assertEquals('11', $msg->dateTimeInformation[0]->dateTime->month);
        $this->assertEquals('2011', $msg->dateTimeInformation[0]->dateTime->year);

        $this->assertEquals(UpdateRefund\DateTimeInformation::OPT_DATE_TICKETED, $msg->dateTimeInformation[1]->businessSemantic);
        $this->assertEquals('15', $msg->dateTimeInformation[1]->dateTime->day);
        $this->assertEquals('11', $msg->dateTimeInformation[1]->dateTime->month);
        $this->assertEquals('2011', $msg->dateTimeInformation[1]->dateTime->year);

        $this->assertCount(2, $msg->referenceInformation->referenceDetails);

        $this->assertCount(1, $msg->ticket);
        $this->assertEquals('17246548165754', $msg->ticket[0]->ticketInformation->documentDetails->number);
        $this->assertNull($msg->ticket[0]->ticketInformation->documentDetails->type);
        $this->assertCount(2, $msg->ticket[0]->ticketGroup);
        $this->assertEquals(UpdateRefund\CouponDetails::COUPON_1, $msg->ticket[0]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnNumber);
        $this->assertEquals(UpdateRefund\CouponDetails::STATUS_REFUNDED, $msg->ticket[0]->ticketGroup[0]->couponInformationDetails->couponDetails->cpnStatus);
        $this->assertNull($msg->ticket[0]->ticketGroup[0]->boardingPriority);
        $this->assertEmpty($msg->ticket[0]->ticketGroup[0]->referenceInformation);
        $this->assertNull($msg->ticket[0]->ticketGroup[0]->actionIdentification);

        $this->assertNull($msg->structuredAddress);

        $this->assertEquals('NOSHOW/REFUND', $msg->travellerInformation->paxDetails->surname);
        $this->assertNull($msg->travellerInformation->paxDetails->type);
        $this->assertNull($msg->travellerInformation->paxDetails->quantity);
        $this->assertNull($msg->travellerInformation->paxDetails->status);

        $this->assertEquals(UpdateRefund\PriceTicketDetails::INDICATOR_INTERNATIONAL_ITINERARY, $msg->pricingDetails->priceTicketDetails->indicators);
        $this->assertNull($msg->pricingDetails->priceTariffType);

        $this->assertEquals(UpdateRefund\CommissionDetails::TYPE_NEW_COMMISSION, $msg->commission->commissionDetails->type);
        $this->assertEquals('P', $msg->commission->commissionDetails->freeText);
        $this->assertEquals(0.00, $msg->commission->commissionDetails->rate);
        $this->assertEquals(0.00, $msg->commission->commissionDetails->amount);

        $this->assertEmpty($msg->commission->otherCommissionDetails);
    }

    /**
     * 5.4 Operation: Partial refund
     *
     * Not all parameters are included in this example, just the ones that aren't covered by another unittest yet
     */
    public function testCanMakePartialRefundMessage()
    {
        $opt = new DocRefundUpdateRefundOptions([
            //Other parameters omitted
            'references' => [
                new Reference([
                    'type' => Reference::TYPE_TKT_INDICATOR,
                    'value' => 'Y'
                ]),
                new Reference([
                    'type' => Reference::TYPE_DATA_SOURCE,
                    'value' => 'F'
                ]),
                new Reference([
                    'type' => 'RA',
                    'value' => '0044444'
                ]),
                new Reference([
                    'type' => Reference::TYPE_INVOICE_NUMBER,
                    'value' => '123456'
                ])
            ],
            'travellerPrioCompany' => '01111111111',
            'travellerPrioDateOfJoining' => \DateTime::createFromFormat('Ymd', '20030529'), //29MAY03
            'travellerPrioReference' => '000222222F',
            'freeText' => [
                new FreeTextOpt([
                    'type' => FreeTextOpt::TYPE_REMARK,
                    'freeText' => 'TEST'
                ])
            ]
        ]);

        $msg = new UpdateRefund($opt);

        $this->assertEquals('29MAY03', $msg->travellerPriorityInfo->dateOfJoining);
        $this->assertEquals('01111111111', $msg->travellerPriorityInfo->company);
        $this->assertEquals('000222222F', $msg->travellerPriorityInfo->travellerReference);

        $this->assertCount(1, $msg->interactiveFreeText);
        $this->assertEquals('TEST', $msg->interactiveFreeText[0]->freeText);
        $this->assertEquals(UpdateRefund\FreeTextQualification::TYPE_REMARK, $msg->interactiveFreeText[0]->freeTextQualification->informationType);
        $this->assertEquals(UpdateRefund\FreeTextQualification::QUAL_CODED_AND_LITERAL_TEXT, $msg->interactiveFreeText[0]->freeTextQualification->textSubjectQualifier);

        $this->assertCount(4, $msg->referenceInformation->referenceDetails);
        $this->assertEquals('Y', $msg->referenceInformation->referenceDetails[0]->value);
        $this->assertEquals(UpdateRefund\ReferenceDetails::TYPE_TKT_INDICATOR, $msg->referenceInformation->referenceDetails[0]->type);
        $this->assertEquals('F', $msg->referenceInformation->referenceDetails[1]->value);
        $this->assertEquals(UpdateRefund\ReferenceDetails::TYPE_DATA_SOURCE, $msg->referenceInformation->referenceDetails[1]->type);
        $this->assertEquals('0044444', $msg->referenceInformation->referenceDetails[2]->value);
        $this->assertEquals('RA', $msg->referenceInformation->referenceDetails[2]->type);
        $this->assertEquals('123456', $msg->referenceInformation->referenceDetails[3]->value);
        $this->assertEquals(UpdateRefund\ReferenceDetails::TYPE_INVOICE_NUMBER, $msg->referenceInformation->referenceDetails[3]->type);
    }

    /**
     * 5.5 Operation: Update a refund
     *
     * Not all parameters are included in this example, just the ones that aren't covered by another unittest yet
     */
    public function testCanMakeUpdateRefundMessage()
    {
        $opt = new DocRefundUpdateRefundOptions([
            //Other parameters omitted
            'ticketNumber' => '17246548165740',
            'tourCode' => '01JUL86LH11A',
            'refundedItinerary' => [
                new RefundItinOpt([
                    'company' => 'AF',
                    'origin' => 'NCE',
                    'destination' => 'PAR'
                ])
            ]
        ]);

        $msg = new UpdateRefund($opt);

        $this->assertEquals('17246548165740', $msg->ticketNumber->documentDetails->number);
        $this->assertNull($msg->ticketNumber->documentDetails->type);

        $this->assertEquals('01JUL86LH11A', $msg->tourInformation->tourInformationDetails->tourCode);

        $this->assertCount(1, $msg->refundedItinerary);
        $this->assertEquals('AF', $msg->refundedItinerary[0]->airlineCodeRfndItinerary->companyIdentification->operatingCompany);
        $this->assertEquals('NCE', $msg->refundedItinerary[0]->originDestinationRfndItinerary->origin);
        $this->assertEquals('PAR', $msg->refundedItinerary[0]->originDestinationRfndItinerary->destination);

        $this->assertEmpty($msg->ticket);
        $this->assertEmpty($msg->interactiveFreeText);
        $this->assertEmpty($msg->fopGroup);
        $this->assertEmpty($msg->taxDetailsInformation);
        $this->assertEmpty($msg->dateTimeInformation);
        $this->assertNull($msg->referenceInformation);
        $this->assertNull($msg->travellerPriorityInfo);
        $this->assertNull($msg->refundedRoute);
        $this->assertNull($msg->travellerInformation);
        $this->assertNull($msg->monetaryInformation);
        $this->assertNull($msg->pricingDetails);
        $this->assertNull($msg->commission);
        $this->assertNull($msg->structuredAddress);
        $this->assertNull($msg->userIdentification);
        $this->assertNull($msg->feeGroup);
        $this->assertNull($msg->firstAndLastSegmentDates);
        $this->assertNull($msg->originAndDestination);
        $this->assertNull($msg->transactionContext);

    }
}

