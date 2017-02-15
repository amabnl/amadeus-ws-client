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

namespace Test\Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;
use Amadeus\Client\RequestOptions\Fop\DataOrSwitch;
use Amadeus\Client\RequestOptions\Fop\ElementRef;
use Amadeus\Client\RequestOptions\Fop\FraudScreeningOptions;
use Amadeus\Client\RequestOptions\Fop\Group;
use Amadeus\Client\RequestOptions\Fop\InstallmentsInfo;
use Amadeus\Client\RequestOptions\Fop\MopInfo;
use Amadeus\Client\RequestOptions\Fop\PaxRef;
use Amadeus\Client\RequestOptions\Fop\Payment;
use Amadeus\Client\RequestOptions\FopCreateFopOptions;
use Amadeus\Client\Struct\Fop\AttributeDetails;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment;
use Amadeus\Client\Struct\Fop\FreeTextDetails;
use Amadeus\Client\Struct\Fop\MonetaryDetails;
use Amadeus\Client\Struct\Fop\ReferenceDetails;
use Amadeus\Client\Struct\Fop\TransactionDetails;
use Test\Amadeus\BaseTestCase;

/**
 * CreateFormOfPaymentTest
 *
 * @package Test\Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class CreateFormOfPaymentTest extends BaseTestCase
{
    /**
     * 5.7 Operation: FOP creation with structured freeflow
     *
     * Query with the structured freeflow, in 2 different fields.
     *
     */
    public function testCanMakeMessageWithStructuredFreeFlow()
    {
        $opt = new FopCreateFopOptions([
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
                            'sequenceNr' => 1,
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'fopCode' => 'VI',
                            'supplementaryData' => [
                                new DataOrSwitch([
                                    'type' => 'FF1',
                                    'description' => '1234'
                                ]),
                                new DataOrSwitch([
                                    'type' => 'FF2',
                                    'description' => 'ABCD-*12'
                                ]),
                            ],
                            'payMerchant' => 'AY',
                            'payments' => [
                                new Payment([
                                    'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                    'amount' => 100,
                                    'currency' => 'EUR'
                                ])
                            ]
                        ]),
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals(TransactionDetails::TRANS_CREATE_FORM_OF_PAYMENT, $msg->transactionContext->transactionDetails->code);
        $this->assertCount(1, $msg->fopGroup);
        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertEquals('AY', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyCode);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails);
        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation);
        $this->assertEquals(MonetaryDetails::TYPE_TOTAL_FARE_AMOUNT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->typeQualifier);
        $this->assertEquals(100, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->amount);
        $this->assertEquals('EUR', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->currency);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->oldFopFreeflow);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->fopMasterElementReference);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->stakeholderPayerReference);
    }

    /**
     * 5.8 Operation: FOP creation with unstructured free flow
     */
    public function testCanMakeMessageWithUnstructuredFreeFlow()
    {
        $opt = new FopCreateFopOptions([
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
                            'sequenceNr' => 1,
                            'fopCode' => 'VI',
                            'freeFlowText' => 'VI4541099100010016/0919'
                        ]),
                        new MopInfo([
                            'sequenceNr' => 2,
                            'fopCode' => 'VI',
                            'freeFlowText' => 'VI4541099100010024/0919/EUR20'
                        ]),
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals(TransactionDetails::TRANS_CREATE_FORM_OF_PAYMENT, $msg->transactionContext->transactionDetails->code);
        $this->assertCount(1, $msg->fopGroup);
        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);

        $this->assertCount(2, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertEquals('VI4541099100010016/0919', $msg->fopGroup[0]->mopDescription[0]->mopDetails->oldFopFreeflow->freeText);
        $this->assertEquals(FreeTextDetails::ENCODING_MUTUAL, $msg->fopGroup[0]->mopDescription[0]->mopDetails->oldFopFreeflow->freeTextDetails->encoding);
        $this->assertEquals(FreeTextDetails::SOURCE_MANUAL, $msg->fopGroup[0]->mopDescription[0]->mopDetails->oldFopFreeflow->freeTextDetails->source);
        $this->assertEquals(FreeTextDetails::QUALIFIER_MUTUAL, $msg->fopGroup[0]->mopDescription[0]->mopDetails->oldFopFreeflow->freeTextDetails->textSubjectQualifier);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->fopMasterElementReference);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->stakeholderPayerReference);

        $this->assertEquals(2, $msg->fopGroup[0]->mopDescription[1]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[1]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertEquals('VI4541099100010024/0919/EUR20', $msg->fopGroup[0]->mopDescription[1]->mopDetails->oldFopFreeflow->freeText);
        $this->assertEquals(FreeTextDetails::ENCODING_MUTUAL, $msg->fopGroup[0]->mopDescription[1]->mopDetails->oldFopFreeflow->freeTextDetails->encoding);
        $this->assertEquals(FreeTextDetails::SOURCE_MANUAL, $msg->fopGroup[0]->mopDescription[1]->mopDetails->oldFopFreeflow->freeTextDetails->source);
        $this->assertEquals(FreeTextDetails::QUALIFIER_MUTUAL, $msg->fopGroup[0]->mopDescription[1]->mopDetails->oldFopFreeflow->freeTextDetails->textSubjectQualifier);
    }

    /**
     * 5.9 Operation: FOP with installments
     *
     * Query containing Installments data.
     */
    public function testCanMakeMessageWithInstallments()
    {
        $opt = new FopCreateFopOptions([
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
                            'sequenceNr' => 1,
                            'fopCode' => 'CCVI',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'QF',
                            'payments' => [
                                new Payment([
                                    'type' => Payment::TYPE_FIRST_INSTALMENT_AMOUNT,
                                    'amount' => 100,
                                    'currency' => 'AUD'
                                ]),
                                new Payment([
                                    'type' => Payment::TYPE_FOLLOWING_INSTALMENT_AMOUNT,
                                    'amount' => 200,
                                    'currency' => 'AUD'
                                ]),
                                new Payment([
                                    'type' => Payment::TYPE_INSTALMENT_INTEREST,
                                    'amount' => 300,
                                    'currency' => 'AUD'
                                ])
                            ],
                            'installmentsInfo' => new InstallmentsInfo([
                                'nrOfInstallments' => 2,
                                'frequency' => InstallmentsInfo::FREQUENCY_MONTHLY,
                                'startDate' => \DateTime::createFromFormat('Ymd', '20130223'),
                                'format' => InstallmentsInfo::FORMAT_YYMMDD
                            ]),
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010016',
                                'expiryDate' => '0913',
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertNull($msg->transactionContext);
        $this->assertEmpty($msg->bestEffort);
        $this->assertNull($msg->reservationControlInformation);

        $this->assertCount(1, $msg->fopGroup);

        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);

        $this->assertEquals('CCVI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->oldFopFreeflow);

        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertEquals('QF', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyNumericCode);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation);
        $this->assertEquals(MonetaryDetails::TYPE_FIRST_INSTALMENT_AMOUNT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->typeQualifier);
        $this->assertEquals(100, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->amount);
        $this->assertEquals('AUD', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->currency);
        $this->assertCount(2, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->otherMonetaryDetails);
        $this->assertEquals(MonetaryDetails::TYPE_FOLLOWING_INSTALMENT_AMOUNT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->otherMonetaryDetails[0]->typeQualifier);
        $this->assertEquals(200, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->otherMonetaryDetails[0]->amount);
        $this->assertEquals('AUD', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->otherMonetaryDetails[0]->currency);
        $this->assertEquals(MonetaryDetails::TYPE_INSTALMENT_INTEREST, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->otherMonetaryDetails[1]->typeQualifier);
        $this->assertEquals(300, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->otherMonetaryDetails[1]->amount);
        $this->assertEquals('AUD', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->otherMonetaryDetails[1]->currency);

        $this->assertEquals(2, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsNumber);
        $this->assertEquals('MTH', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsFrequency);
        $this->assertEquals('130223', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsStartDate);
        $this->assertEquals(101, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsDatrDateFormat);

        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->association);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->currenciesRatesGroup);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->distributionChannelInformation);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->expirationPeriod);

        $this->assertEquals('CC', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fopInformation->formOfPayment->type);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->dummy);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->vendorCode);
        $this->assertEquals('4541099100010016', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->cardNumber);
        $this->assertEquals('0913', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentStatus);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->fopMasterElementReference);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->stakeholderPayerReference);
    }

    /**
     * 5.10 Operation: Fraud screening
     *
     * Query with fraud screening data.
     */
    public function testCanMakeMessageWithFraudScreening()
    {
        $opt = new FopCreateFopOptions([
            'fopGroup' => [
                new Group([
                    'paxRef' => [
                        new PaxRef([
                            'type' => PaxRef::TYPE_ADULT,
                            'value' => 1
                        ])
                    ],
                    'mopInfo' => [
                        new MopInfo([
                            'sequenceNr' => 1,
                            'fopCode' => 'VI',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'AY',
                            'installmentsInfo' => new InstallmentsInfo([
                                'nrOfInstallments' => 1
                            ]),
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010016',
                                'expiryDate' => '0915',
                                'name' => 'CLEMENT DUPONT'
                            ]),
                            'fraudScreening' => new FraudScreeningOptions([

                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertNull($msg->transactionContext);
        $this->assertEmpty($msg->bestEffort);
        $this->assertNull($msg->reservationControlInformation);
    }
}
