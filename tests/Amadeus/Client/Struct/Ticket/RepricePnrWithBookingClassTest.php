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

namespace Test\Amadeus\Client\Struct\Ticket;

use Amadeus\Client\RequestOptions\Fare\PricePnr\AwardPricing;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ExemptTax;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\Tax;
use Amadeus\Client\RequestOptions\Ticket\ExchangeInfoOptions;
use Amadeus\Client\RequestOptions\Ticket\MultiRefOpt;
use Amadeus\Client\RequestOptions\Ticket\PaxSegRef;
use Amadeus\Client\RequestOptions\TicketRepricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\FirstCurrencyDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\LocationInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\ReferenceDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxData;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxInformation;
use Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass;
use Test\Amadeus\BaseTestCase;

/**
 * RepricePnrWithBookingClassTest
 *
 * @package Test\Amadeus\Client\Struct\Ticket
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RepricePnrWithBookingClassTest extends BaseTestCase
{
    /**
     * 5.1 Operation: Reprice of TKT
     *
     * Reprice ticket 999-8550225521
     */
    public function testCanConstructMessage()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                'number' => 1,
                'eTickets' => [
                    '9998550225521'
                    ]
                ])
            ],
            'multiReferences' => [
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 3,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ]),
                        new PaxSegRef([
                            'reference' => 4,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ])
                    ]
                ]),
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_PASSENGER_ADULT
                        ]),
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_SERVICE
                        ])
                    ]
                ]),
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->exchangeInformationGroup);
        $this->assertCount(1, $msg->exchangeInformationGroup[0]->documentInfoGroup);
        $this->assertCount(1, $msg->exchangeInformationGroup[0]->transactionIdentifier->itemNumberDetails);
        $this->assertEquals(1, $msg->exchangeInformationGroup[0]->transactionIdentifier->itemNumberDetails[0]->number);
        $this->assertEquals('9998550225521', $msg->exchangeInformationGroup[0]->documentInfoGroup[0]->paperticketDetailsLastCoupon->documentDetails->number);
        $this->assertEquals(RepricePnrWithBookingClass\DocumentDetails::TYPE_ELECTRONIC_TICKET, $msg->exchangeInformationGroup[0]->documentInfoGroup[0]->paperticketDetailsLastCoupon->documentDetails->type);

        $this->assertCount(2, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_PAX_SEG_LINE_TST_SELECTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(3, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_PAX_SEG_LINE_TST_SELECTION, $msg->pricingOption[1]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[1]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_ADULT, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_ELEMENT, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->type);
    }

    public function testCanConstructMessagePaperTicket()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'paperTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'multiReferences' => [
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 3,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ]),
                        new PaxSegRef([
                            'reference' => 4,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ])
                    ]
                ]),
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_PASSENGER_ADULT
                        ]),
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_SERVICE
                        ])
                    ]
                ]),
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->exchangeInformationGroup);
        $this->assertCount(1, $msg->exchangeInformationGroup[0]->documentInfoGroup);
        $this->assertEquals(1, $msg->exchangeInformationGroup[0]->transactionIdentifier->itemNumberDetails[0]->number);
        $this->assertEquals('9998550225521', $msg->exchangeInformationGroup[0]->documentInfoGroup[0]->paperticketDetailsLastCoupon->documentDetails->number);
        $this->assertEquals(RepricePnrWithBookingClass\DocumentDetails::TYPE_PAPER_TICKET, $msg->exchangeInformationGroup[0]->documentInfoGroup[0]->paperticketDetailsLastCoupon->documentDetails->type);

        $this->assertCount(2, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_PAX_SEG_LINE_TST_SELECTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(3, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_PAX_SEG_LINE_TST_SELECTION, $msg->pricingOption[1]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[1]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_ADULT, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_ELEMENT, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->type);
    }

    public function testCanConstructMessageWithMultipleNumbers()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => [1, 2],
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'multiReferences' => [
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 3,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ]),
                        new PaxSegRef([
                            'reference' => 4,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ])
                    ]
                ]),
                new MultiRefOpt([
                    'references' => [
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_PASSENGER_ADULT
                        ]),
                        new PaxSegRef([
                            'reference' => 1,
                            'type' => PaxSegRef::TYPE_SERVICE
                        ])
                    ]
                ]),
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->exchangeInformationGroup);
        $this->assertCount(1, $msg->exchangeInformationGroup[0]->documentInfoGroup);
        $this->assertCount(2, $msg->exchangeInformationGroup[0]->transactionIdentifier->itemNumberDetails);
        $this->assertEquals(1, $msg->exchangeInformationGroup[0]->transactionIdentifier->itemNumberDetails[0]->number);
        $this->assertEquals(2, $msg->exchangeInformationGroup[0]->transactionIdentifier->itemNumberDetails[1]->number);
        $this->assertEquals('9998550225521', $msg->exchangeInformationGroup[0]->documentInfoGroup[0]->paperticketDetailsLastCoupon->documentDetails->number);
        $this->assertEquals(RepricePnrWithBookingClass\DocumentDetails::TYPE_ELECTRONIC_TICKET, $msg->exchangeInformationGroup[0]->documentInfoGroup[0]->paperticketDetailsLastCoupon->documentDetails->type);

        $this->assertCount(2, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_PAX_SEG_LINE_TST_SELECTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(3, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_PAX_SEG_LINE_TST_SELECTION, $msg->pricingOption[1]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[1]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_ADULT, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_ELEMENT, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->type);
    }

    public function testCanMakeMessageWithWaiverCode()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'waiverCode' => 'IL'
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_WAIVER_OPTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertNull($msg->pricingOption[0]->paxSegTstReference);
        $this->assertCount(1, $msg->pricingOption[0]->optionDetail->criteriaDetails);
        $this->assertEquals('IL', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertNull($msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeDescription);
    }

    public function testCanMakeMessageWithValidatingCarrier()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'validatingCarrier' => 'BA'
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_VALIDATING_CARRIER, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertNull($msg->pricingOption[0]->paxSegTstReference);
        $this->assertEquals('BA', $msg->pricingOption[0]->carrierInformation->companyIdentification->otherCompany);
    }

    public function testCanMakeMessageWithForceBreakPoint()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'forceBreakPointRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 2
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 4
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_FORCE_FEE_BREAK_POINT, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);

        $this->assertEquals(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->couponInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertEmpty($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->monetaryInformation);
        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->penDisInformation);
        $this->assertEmpty($msg->pricingOption[0]->taxInformation);
    }

    public function testCanMakeMessageWithBreakPointProhibited()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'noBreakPointRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 2
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 4
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_NO_BREAKPOINT, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);

        $this->assertEquals(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->couponInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertEmpty($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->monetaryInformation);
        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->penDisInformation);
        $this->assertEmpty($msg->pricingOption[0]->taxInformation);
    }

    public function testCanMakeMessageWithSimpleOverride()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'overrideOptions' => [
                TicketRepricePnrWithBookingClassOptions::OVERRIDE_NO_JOURNEY_TURNAROUND_POINT
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_NO_JOURNEY_TURNAROUND_POINT, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertNull($msg->pricingOption[0]->paxSegTstReference);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->couponInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertEmpty($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->monetaryInformation);
        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->penDisInformation);
        $this->assertEmpty($msg->pricingOption[0]->taxInformation);
    }

    public function testCanMakeMessageNoOption()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_NO_OPTION, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertNull($msg->pricingOption[0]->paxSegTstReference);
        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->couponInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertEmpty($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->monetaryInformation);
        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertNull($msg->pricingOption[0]->penDisInformation);
        $this->assertEmpty($msg->pricingOption[0]->taxInformation);
    }

    public function testCanMakeMessagePassengerPtcDiscount()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'paxDiscountCodes' => [
                'YTH',
                'AD20',
                'MIL',
            ],
            'paxDiscountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ]),
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SEGMENT,
                    'reference' => 4
                ])
            ],
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_PASSENGER_DISCOUNT_PTC, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);

        $this->assertEquals(PenDisInformation::QUAL_DISCOUNT, $msg->pricingOption[0]->penDisInformation->discountPenaltyQualifier);
        $this->assertCount(3, $msg->pricingOption[0]->penDisInformation->discountPenaltyDetails);
        $this->assertEquals('YTH', $msg->pricingOption[0]->penDisInformation->discountPenaltyDetails[0]->rate);
        $this->assertNull($msg->pricingOption[0]->penDisInformation->discountPenaltyDetails[0]->amount);
        $this->assertNull($msg->pricingOption[0]->penDisInformation->discountPenaltyDetails[0]->amountType);
        $this->assertNull($msg->pricingOption[0]->penDisInformation->discountPenaltyDetails[0]->currency);
        $this->assertNull($msg->pricingOption[0]->penDisInformation->discountPenaltyDetails[0]->function);
        $this->assertEquals('AD20', $msg->pricingOption[0]->penDisInformation->discountPenaltyDetails[1]->rate);
        $this->assertEquals('MIL', $msg->pricingOption[0]->penDisInformation->discountPenaltyDetails[2]->rate);

        $this->assertCount(2, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(4, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);

        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->couponInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertEmpty($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->monetaryInformation);
        $this->assertNull($msg->pricingOption[0]->optionDetail);
        $this->assertEmpty($msg->pricingOption[0]->taxInformation);
    }

    public function testCanMakeMessageWithCorpUnifareAwardPricing()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'corporateUniFares' => [
                '012345',
                '456789'
            ],
            'awardPricing' => new AwardPricing([
                'carrier' => '6X',
                'tierLevel' => 'GOLD'
            ])
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(2, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_CORPORATE_UNIFARES, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[0]->optionDetail->criteriaDetails);
        $this->assertEquals('012345', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEquals('456789', $msg->pricingOption[0]->optionDetail->criteriaDetails[1]->attributeType);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_AWARD, $msg->pricingOption[1]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('6X', $msg->pricingOption[1]->carrierInformation->companyIdentification->otherCompany);
        $this->assertCount(1, $msg->pricingOption[1]->frequentFlyerInformation->frequentTravellerDetails);
        $this->assertEquals('GOLD', $msg->pricingOption[1]->frequentFlyerInformation->frequentTravellerDetails[0]->tierLevel);
        $this->assertNull($msg->pricingOption[1]->frequentFlyerInformation->frequentTravellerDetails[0]->carrier);
        $this->assertNull($msg->pricingOption[1]->frequentFlyerInformation->frequentTravellerDetails[0]->number);
        $this->assertNull($msg->pricingOption[1]->frequentFlyerInformation->frequentTravellerDetails[0]->priorityCode);
    }

    public function testCanMakeMessageWithFareBasisOverride()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'pricingsFareBasis' => [
                new FareBasis([
                    'fareBasisCode' => 'LDLXNSSA',
                    'references' => [
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_PASSENGER,
                            'reference' => 1
                        ]),
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 3
                        ]),
                        new PaxSegRef([
                            'type' => PaxSegRef::TYPE_SEGMENT,
                            'reference' => 4
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOption[0]->optionDetail->criteriaDetails);
        $this->assertEquals('LDLXNSSA', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);

        $this->assertCount(3, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(3, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertEquals(4, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[2]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_SEGMENT_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[2]->type);
    }

    public function testCanMakeMessageWithTaxes()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'taxes' => [
                new Tax([
                    'taxNature' => 'GO',
                    'countryCode' => 'ZV',
                    'amount' => 50
                ]),
                new Tax([
                    'taxNature' => null,
                    'countryCode' => 'FR',
                    'percentage' => 10
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_ADD_TAX, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(2, $msg->pricingOption[0]->taxInformation);
        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $msg->pricingOption[0]->taxInformation[0]->taxQualifier);
        $this->assertEquals('GO', $msg->pricingOption[0]->taxInformation[0]->taxNature);
        $this->assertEquals(50, $msg->pricingOption[0]->taxInformation[0]->taxData->taxRate);
        $this->assertEquals(TaxData::QUALIFIER_AMOUNT, $msg->pricingOption[0]->taxInformation[0]->taxData->taxValueQualifier);
        $this->assertEquals('ZV', $msg->pricingOption[0]->taxInformation[0]->taxType->isoCountry);

        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $msg->pricingOption[0]->taxInformation[1]->taxQualifier);
        $this->assertNull($msg->pricingOption[0]->taxInformation[1]->taxNature);
        $this->assertEquals(10, $msg->pricingOption[0]->taxInformation[1]->taxData->taxRate);
        $this->assertEquals(TaxData::QUALIFIER_PERCENTAGE, $msg->pricingOption[0]->taxInformation[1]->taxData->taxValueQualifier);
        $this->assertEquals('FR', $msg->pricingOption[0]->taxInformation[1]->taxType->isoCountry);
    }



    public function testCanMakeMessageWithExemptFromTaxes()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'exemptTaxes' => [
                new ExemptTax([
                    'taxNature' => 'GO',
                    'countryCode' => 'ZV',
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);
        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_EXEMPT_TAXES, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOption[0]->taxInformation);
        $this->assertEquals(TaxInformation::QUALIFIER_TAX, $msg->pricingOption[0]->taxInformation[0]->taxQualifier);
        $this->assertEquals('GO', $msg->pricingOption[0]->taxInformation[0]->taxNature);
        $this->assertNull($msg->pricingOption[0]->taxInformation[0]->taxData);
        $this->assertEquals('ZV', $msg->pricingOption[0]->taxInformation[0]->taxType->isoCountry);
    }

    public function testCanMakeMessageWithCurrencyOverride()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'currencyOverride' => 'USD'
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('USD', $msg->pricingOption[0]->currency->firstCurrencyDetails->currencyIsoCode);
        $this->assertEquals(FirstCurrencyDetails::QUAL_CURRENCY_OVERRIDE, $msg->pricingOption[0]->currency->firstCurrencyDetails->currencyQualifier);
    }

    public function testCanMakeMessageWithPointOverrides()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'pointOfSaleOverride' => 'LON',
            'pointOfTicketingOverride' => 'MAN'
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(2, $msg->pricingOption);
        
        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_POINT_OF_SALE_OVERRIDE, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(LocationInformation::TYPE_POINT_OF_SALE, $msg->pricingOption[0]->locationInformation->locationType);
        $this->assertEquals('LON', $msg->pricingOption[0]->locationInformation->firstLocationDetails->code);
        $this->assertEquals(RepricePnrWithBookingClass\PricingOptionKey::OPTION_POINT_OF_TICKETING_OVERRIDE, $msg->pricingOption[1]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals(LocationInformation::TYPE_POINT_OF_TICKETING, $msg->pricingOption[1]->locationInformation->locationType);
        $this->assertEquals('MAN', $msg->pricingOption[1]->locationInformation->firstLocationDetails->code);
    }

    public function testCanMakeMessageWithOverrideReusableAmount()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'overrideReusableAmountRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_SERVICE,
                    'reference' => 1
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertCount(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_ELEMENT, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);
    }

    public function testWillNotDoubleOverride()
    {
        $opt = new TicketRepricePnrWithBookingClassOptions([
            'exchangeInfo' => [
                new ExchangeInfoOptions([
                    'number' => 1,
                    'eTickets' => [
                        '9998550225521'
                    ]
                ])
            ],
            'overrideOptions' => [
                TicketRepricePnrWithBookingClassOptions::OVERRIDE_NEGOTIATED_FARE, 
                TicketRepricePnrWithBookingClassOptions::OVERRIDE_FARE_BASIS_SIMPLE_OVERRIDE
            ],
            'validatingCarrier' => 'BA',
            'currencyOverride' => 'EUR',
            'pricingsFareBasis' => [
                new FareBasis([
                    'fareBasisCode' => 'QNC469W2',
                    'references' => [
                        new PaxSegRef([
                            'reference' => 2,
                            'type' => PaxSegRef::TYPE_SEGMENT
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new RepricePnrWithBookingClass($opt);

        $this->assertCount(4, $msg->pricingOption);

        $validatingCarrierPo = new RepricePnrWithBookingClass\PricingOption(RepricePnrWithBookingClass\PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOption, $validatingCarrierPo));

        $currencyOverridePo = new RepricePnrWithBookingClass\PricingOption(RepricePnrWithBookingClass\PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOption, $currencyOverridePo));

        $fareBasisOverridePo = new RepricePnrWithBookingClass\PricingOption(RepricePnrWithBookingClass\PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference(
            [new PaxSegRef(['type'=> PaxSegRef::TYPE_SEGMENT, 'reference' => 2])]
        );

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOption, $fareBasisOverridePo));

        $negofarePo = new RepricePnrWithBookingClass\PricingOption(RepricePnrWithBookingClass\PricingOptionKey::OPTION_NEGOTIATED_FARE);

        $this->assertTrue($this->assertArrayContainsSameObject($msg->pricingOption, $negofarePo));
    }
}
