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
use Amadeus\Client\RequestOptions\Fop\InvoiceInfo;
use Amadeus\Client\RequestOptions\Fop\MopInfo;
use Amadeus\Client\RequestOptions\Fop\ObFeeComputation;
use Amadeus\Client\RequestOptions\Fop\PaxRef;
use Amadeus\Client\RequestOptions\Fop\PayId;
use Amadeus\Client\RequestOptions\Fop\Payment;
use Amadeus\Client\RequestOptions\Fop\PaySupData;
use Amadeus\Client\RequestOptions\FopCreateFopOptions;
use Amadeus\Client\Struct\Fop\AttributeDetails;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment;
use Amadeus\Client\Struct\Fop\DeviceIdentification;
use Amadeus\Client\Struct\Fop\FormOfPayment;
use Amadeus\Client\Struct\Fop\FreeTextDetails;
use Amadeus\Client\Struct\Fop\InvoiceFormOfPayment;
use Amadeus\Client\Struct\Fop\MonetaryDetails;
use Amadeus\Client\Struct\Fop\PassengerReference;
use Amadeus\Client\Struct\Fop\PaymentId;
use Amadeus\Client\Struct\Fop\ReferenceDetails;
use Amadeus\Client\Struct\Fop\StatusInformation;
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
     * TODO
     * Empty nodes:
     * authorisationSupplementaryData
     * fopReference
     */

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

        $this->assertNull($msg->fopGroup[0]->pricingTicketingDetails);
        $this->assertNull($msg->fopGroup[0]->feeTypeInfo);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);

        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData);
        $this->assertEquals('D', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaSetType);
        $this->assertCount(2, $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails);
        $this->assertEquals('FF1', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[0]->attributeType);
        $this->assertEquals('1234', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[0]->attributeDescription);
        $this->assertEquals('FF2', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[1]->attributeType);
        $this->assertEquals('ABCD-*12', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[1]->attributeDescription);

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
                                'ipAddress' => '111.222.333.444', // /r/itsaunixsystem
                                'firstName' => 'CLEMENT',
                                'lastName' => 'DUPONT',
                                'dateOfBirth' => \DateTime::createFromFormat('dmY', '30101980'),
                                'idDocumentNr' => '25208731592',
                                'idDocumentType' => FraudScreeningOptions::ID_DOC_CPF__BRAZILIAN_SECURITY_NUMBER,
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

        $this->assertEmpty($msg->fopGroup[0]->pnrElementAssociation);

        $this->assertCount(1, $msg->fopGroup[0]->passengerAssociation);
        $this->assertEquals(1, $msg->fopGroup[0]->passengerAssociation[0]->passengerReference->value);
        $this->assertEquals(PassengerReference::TYPE_ADULT, $msg->fopGroup[0]->passengerAssociation[0]->passengerReference->type);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);
        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopBillingCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopEdiCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopElecTicketingCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopMapTable);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopPrintedCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopReportingCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopStatus);

        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertEquals('AY', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyNumericCode);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsNumber);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsDatrDateFormat);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsFrequency);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->extendedPaymentInfo->extendedPaymentDetails->instalmentsStartDate);

        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation);

        $this->assertEquals(StatusInformation::ACTION_YES, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->fraudScreening->statusInformation->action);
        $this->assertEquals(StatusInformation::INDICATOR_FRAUD_SCREENING, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->fraudScreening->statusInformation->indicator);

        $this->assertEquals('111.222.333.444', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->ipAdress->deviceIdentification->address);
        $this->assertEquals(DeviceIdentification::QUAL_IP_ADDRESS, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->ipAdress->deviceIdentification->qualifier);

        $this->assertEquals('DUPONT', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerName->ccHolderNameDetails->surname);
        $this->assertEquals('CLEMENT', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerName->otherNameDetails->givenName);

        $this->assertEquals('1980', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerDateOfBirth->dateTime->year);
        $this->assertEquals('10', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerDateOfBirth->dateTime->month);
        $this->assertEquals('30', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerDateOfBirth->dateTime->day);

        $this->assertEquals(ReferenceDetails::TYPE_CPF_BRAZILIAN_SECURITY_NUMBER, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->formOfIdDetails->referenceDetails->type);
        $this->assertEquals('25208731592', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->formOfIdDetails->referenceDetails->value);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->billingAddress);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->merchantURL);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerPhoneOrEmail);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->securityCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->shopperDetails);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->shopperSession);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->travelShopper);

        $this->assertEquals('CC', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fopInformation->formOfPayment->type);

        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->vendorCode);
        $this->assertEquals('4541099100010016', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->cardNumber);
        $this->assertEquals('0915', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertEquals('CLEMENT DUPONT', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->ccHolderName);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->asyncDataGroup);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->dummy);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fundTransferData);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->webAccountData);
    }

    /**
     * 5.5 Operation: DCC offer
     *
     * The TFOPCQ will retry the PRI and the DCC currency chosen
     * in order to perform an authorization request to the PSP
     * with the correct currency chosen by the customer.
     */
    public function testCanMakeMessageWithDCCOffer()
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
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'AY',
                            'payments' => [
                                new Payment([
                                    'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                    'amount' => 100,
                                    'currency' => 'EUR'
                                ])
                            ],
                            'payIds' => [
                                new PayId([
                                    'type' => PayId::TYPE_DCC_CURRENCY_CHOSEN,
                                    'id' => 'EUR'
                                ]),
                                new PayId([
                                    'type' => PayId::TYPE_PAYMENT_RECORD_ID,
                                    'id' => 123456
                                ]),
                            ]
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals('FP', $msg->transactionContext->transactionDetails->code);
        $this->assertEmpty($msg->bestEffort);
        $this->assertNull($msg->reservationControlInformation);

        $this->assertCount(1, $msg->fopGroup);

        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertCount(0, $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData);

        $this->assertEquals('AY', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails);
        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation);
        $this->assertEquals(MonetaryDetails::TYPE_TOTAL_FARE_AMOUNT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->typeQualifier);
        $this->assertEquals(100, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->amount);
        $this->assertEquals('EUR', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->currency);

        $this->assertCount(2, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->paymentId);
        $this->assertEquals(PaymentId::TYPE_DCC_CURRENCY_CHOSEN, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->paymentId[0]->referenceType);
        $this->assertEquals('EUR', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->paymentId[0]->uniqueReference);
    }

    /**
     * 5.6 Operation: FOP account (ELV)
     *
     * Query with ELV account data.
     */
    public function testCanMakeMessageWithFopAccountElv()
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
                            'fopCode' => 'ELV',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'supplementaryData' => [
                                new DataOrSwitch([
                                    'type' => '27',
                                    'description' => 'JOHN SMITH'
                                ])
                            ],
                            'payMerchant' => 'AB',
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_ACCOUNT_PAYMENT,
                            'transactionDate' => \DateTime::createFromFormat('dmY', '29112015'),
                            'invoiceInfo' => new InvoiceInfo([
                                'formOfPayment' => InvoiceInfo::FOP_ACCOUNT_PAYMENT,
                                'customerAccount' => '12345678',
                                'membershipStatus' => 'ELV',
                                'routingStation' => 'AB'
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals('FP', $msg->transactionContext->transactionDetails->code);
        $this->assertEmpty($msg->bestEffort);
        $this->assertNull($msg->reservationControlInformation);

        $this->assertCount(1, $msg->fopGroup);

        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('ELV', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData);
        $this->assertEquals('D', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaSetType);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails);
        $this->assertEquals('27', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[0]->attributeType);
        $this->assertEquals('JOHN SMITH', $msg->fopGroup[0]->mopDescription[0]->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[0]->attributeDescription);

        $this->assertEquals('AB', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyCode);
        $this->assertEquals(29, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->dateTime->day);
        $this->assertEquals(11, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->dateTime->month);
        $this->assertEquals(2015, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->dateTime->year);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->dateTime->hour);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->dateTime->milliseconds);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->dateTime->minutes);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->dateTime->seconds);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->transactionDateTime->businessSemantic);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails);
        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->monetaryInformation);

        $this->assertEquals(FormOfPayment::TYPE_ACCOUNT_PAYMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fopInformation->formOfPayment->type);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->dummy);

        $this->assertEquals(InvoiceFormOfPayment::TYPE_ACCOUNT_PAYMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->type);
        $this->assertEquals('12345678', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->customerAccount);
        $this->assertEquals('ELV', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->membershipStatus);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->expiryDate);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->indicator);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->transactionInfo);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->merchantCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->pinCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->formOfPayment->pinCodeType);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->invoiceInformation->otherFormOfPayment);

        $this->assertEquals('AB', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->routing->routingDetails->otherStation);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->accountSupplementaryData);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->bookingReference);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->fopInformationGroup);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->iruQualifier);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->parentTicketGroup);
        $this->assertEmpty($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->invoiceDataGroup->ruleList);
    }

    /**
     * 5.4 Operation: DBI - TP card
     *
     * According to the context and for TP cards,
     * the agent may be requested to input DBI data (Descriptive Billing Information)
     * within the authorization request.
     */
    public function testCanMakeMessageWithDescriptiveBillingInfo()
    {
        $opt = new FopCreateFopOptions([
            'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
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
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'paySupData' => [
                                new PaySupData([
                                    'function' => 'DBI',
                                    'data' => [
                                        ' KS' => '12345',
                                        'RZ' => 'NCE',
                                        ' AE' => '4',
                                        'AU' => '526',
                                        'PK' => '1234',
                                    ]
                                ])
                            ],
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'TP',
                                'cardNumber' => '4541099999990013',
                                'expiryDate' => '0916'
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals('DEF', $msg->transactionContext->transactionDetails->code);
        $this->assertEmpty($msg->bestEffort);
        $this->assertNull($msg->reservationControlInformation);

        $this->assertCount(1, $msg->fopGroup);

        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails);
        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData);
        $this->assertEquals('DBI', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeFunction);

        $this->assertCount(5, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails);
        $this->assertEquals(' KS', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[0]->attributeType);
        $this->assertEquals('12345', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[0]->attributeDescription);
        $this->assertEquals('RZ', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[1]->attributeType);
        $this->assertEquals('NCE', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[1]->attributeDescription);
        $this->assertEquals(' AE', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[2]->attributeType);
        $this->assertEquals('4', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[2]->attributeDescription);
        $this->assertEquals('AU', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[3]->attributeType);
        $this->assertEquals('526', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[3]->attributeDescription);
        $this->assertEquals('PK', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[4]->attributeType);
        $this->assertEquals('1234', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentSupplementaryData[0]->attributeDetails[4]->attributeDescription);


        $this->assertEquals('CC', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fopInformation->formOfPayment->type);

        $this->assertEquals('TP', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->vendorCode);
        $this->assertEquals('4541099999990013', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->cardNumber);
        $this->assertEquals('0916', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->ccHolderName);
    }

    /**
     * 5.14 Operation: Manual approval code
     *
     * The use may want to input a FOP with manual approval code.
     * Following example shows how to input a FOP with manual approval code 12346.
     */
    public function testCanMakeMessageWithManualApprovalCode()
    {
        $opt = new FopCreateFopOptions([
            'transactionCode' => FopCreateFopOptions::TRANS_CREATE_FORM_OF_PAYMENT,
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
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010016',
                                'expiryDate' => '0913',
                                'approvalCode' => '12346',
                                'sourceOfApproval' => CreditCardInfo::APPROVAL_SOURCE_MANUAL
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEmpty( $msg->fopGroup[0]->pnrElementAssociation);

        $this->assertCount(1, $msg->fopGroup[0]->passengerAssociation);
        $this->assertEquals(PassengerReference::TYPE_ADULT, $msg->fopGroup[0]->passengerAssociation[0]->passengerReference->type);
        $this->assertEquals(1, $msg->fopGroup[0]->passengerAssociation[0]->passengerReference->value);

        $this->assertEquals('CC', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fopInformation->formOfPayment->type);

        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->vendorCode);
        $this->assertEquals('4541099100010016', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->cardNumber);
        $this->assertEquals('0913', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->ccHolderName);

        $this->assertEquals('0913', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->authorisationSupplementaryData);
        $this->assertEquals('12346', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->approvalDetails->approvalCodeData->approvalCode);
        $this->assertEquals('M', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->approvalDetails->approvalCodeData->sourceOfApproval);
    }


    /**
     * 5.16 Operation: Multiple FOPs
     *
     * It is possible to specify up to three FOPs per FP element
     */
    public function testCanMakeMessageMultipleFops()
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
                            'fopCode' => 'CASH',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CASH,
                        ]),
                        new MopInfo([
                            'sequenceNr' => 2,
                            'fopCode' => 'CCVI',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'AF',
                            'payments' => [
                                new Payment([
                                    'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                    'amount' => 40,
                                    'currency' => 'EUR'
                                ])
                            ],
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010016',
                                'expiryDate' => '1019',
                                'securityId' => '123'
                            ])
                        ]),
                        new MopInfo([
                            'sequenceNr' => 3,
                            'fopCode' => 'CCVI',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'AF',
                            'payments' => [
                                new Payment([
                                    'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                    'amount' => 20,
                                    'currency' => 'EUR'
                                ])
                            ],
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010024',
                                'expiryDate' => '1019',
                                'securityId' => '123'
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals(TransactionDetails::TRANS_CREATE_FORM_OF_PAYMENT, $msg->transactionContext->transactionDetails->code);

        $this->assertEmpty($msg->bestEffort);
        $this->assertNull($msg->reservationControlInformation);

        $this->assertCount(1, $msg->fopGroup);

        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_TST, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);

        $this->assertCount(3, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);

        $this->assertEquals('CASH', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails);
        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertEquals('CA', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fopInformation->formOfPayment->type);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->dummy);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData);


        $this->assertEquals(2, $msg->fopGroup[0]->mopDescription[1]->fopSequenceNumber->sequenceDetails->number);

        $this->assertEquals('CCVI', $msg->fopGroup[0]->mopDescription[1]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[1]->paymentModule->groupUsage->attributeDetails);
        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[1]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[1]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertEquals('AF', $msg->fopGroup[0]->mopDescription[1]->paymentModule->paymentData->merchantInformation->companyCode);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[1]->paymentModule->paymentData->monetaryInformation);
        $this->assertEquals(MonetaryDetails::TYPE_TOTAL_FARE_AMOUNT, $msg->fopGroup[0]->mopDescription[1]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->typeQualifier);
        $this->assertEquals(40, $msg->fopGroup[0]->mopDescription[1]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->amount);
        $this->assertEquals('EUR', $msg->fopGroup[0]->mopDescription[1]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->currency);

        $this->assertEquals('CC', $msg->fopGroup[0]->mopDescription[1]->paymentModule->mopInformation->fopInformation->formOfPayment->type);
        $this->assertNull($msg->fopGroup[0]->mopDescription[1]->paymentModule->mopInformation->dummy);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[1]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->vendorCode);
        $this->assertEquals('4541099100010016', $msg->fopGroup[0]->mopDescription[1]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->cardNumber);
        $this->assertEquals('1019', $msg->fopGroup[0]->mopDescription[1]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertEquals(123, $msg->fopGroup[0]->mopDescription[1]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->securityId);
        $this->assertNull($msg->fopGroup[0]->mopDescription[1]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->ccHolderName);


        $this->assertEquals(3, $msg->fopGroup[0]->mopDescription[2]->fopSequenceNumber->sequenceDetails->number);

        $this->assertEquals('CCVI', $msg->fopGroup[0]->mopDescription[2]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[2]->paymentModule->groupUsage->attributeDetails);
        $this->assertEquals(AttributeDetails::TYPE_FP_ELEMENT, $msg->fopGroup[0]->mopDescription[2]->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertNull($msg->fopGroup[0]->mopDescription[2]->paymentModule->groupUsage->attributeDetails[0]->attributeDescription);

        $this->assertEquals('AF', $msg->fopGroup[0]->mopDescription[2]->paymentModule->paymentData->merchantInformation->companyCode);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[2]->paymentModule->paymentData->monetaryInformation);
        $this->assertEquals(MonetaryDetails::TYPE_TOTAL_FARE_AMOUNT, $msg->fopGroup[0]->mopDescription[2]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->typeQualifier);
        $this->assertEquals(20, $msg->fopGroup[0]->mopDescription[2]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->amount);
        $this->assertEquals('EUR', $msg->fopGroup[0]->mopDescription[2]->paymentModule->paymentData->monetaryInformation[0]->monetaryDetails->currency);

        $this->assertEquals('CC', $msg->fopGroup[0]->mopDescription[2]->paymentModule->mopInformation->fopInformation->formOfPayment->type);
        $this->assertNull($msg->fopGroup[0]->mopDescription[2]->paymentModule->mopInformation->dummy);
        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[2]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->vendorCode);
        $this->assertEquals('4541099100010024', $msg->fopGroup[0]->mopDescription[2]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->cardNumber);
        $this->assertEquals('1019', $msg->fopGroup[0]->mopDescription[2]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertEquals(123, $msg->fopGroup[0]->mopDescription[2]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->securityId);
        $this->assertNull($msg->fopGroup[0]->mopDescription[2]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->ccHolderName);
    }

    /**
     * 5.17 Operation: Multiple TST -TSM
     *
     * Query to create an FP element on several TSM.
     */
    public function testCanMakeMessageWithMultipleTstTsm()
    {
        $opt = new FopCreateFopOptions([
            'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
            'fopGroup' => [
                new Group([
                    'paxRef' => [
                        new PaxRef([
                            'type' => PaxRef::TYPE_ADULT,
                            'value' => 1
                        ])
                    ],
                    'elementRef' => [
                        new ElementRef([
                            'type' => ElementRef::TYPE_SERVICE_PRICING_RECORD_TATTOO,
                            'value' => 1
                        ]),
                        new ElementRef([
                            'type' => ElementRef::TYPE_SERVICE_PRICING_RECORD_TATTOO,
                            'value' => 2
                        ]),
                        new ElementRef([
                            'type' => ElementRef::TYPE_SPECIAL_SERVICE_REQUEST,
                            'value' => 1
                        ])
                    ],
                    'mopInfo' => [
                        new MopInfo([
                            'sequenceNr' => 1,
                            'fopCode' => 'CASH'
                        ]),
                        new MopInfo([
                            'sequenceNr' => 2,
                            'fopCode' => 'VI',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'AY',
                            'payments' => [
                                new Payment([
                                    'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                    'amount' => 40,
                                    'currency' => 'EUR'
                                ])
                            ],
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010016',
                                'expiryDate' => '0919'
                            ])
                        ]),
                        new MopInfo([
                            'sequenceNr' => 3,
                            'fopCode' => 'VI',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'AY',
                            'payments' => [
                                new Payment([
                                    'type' => Payment::TYPE_TOTAL_FARE_AMOUNT,
                                    'amount' => 20,
                                    'currency' => 'EUR'
                                ])
                            ],
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010024',
                                'expiryDate' => '0919'
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals(TransactionDetails::TRANS_AUTH_ON_TICKET_MCO_EMD, $msg->transactionContext->transactionDetails->code);

        $this->assertEmpty($msg->bestEffort);
        $this->assertNull($msg->reservationControlInformation);

        $this->assertCount(1, $msg->fopGroup);

        $this->assertCount(3, $msg->fopGroup[0]->pnrElementAssociation);
        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_SERVICE_PRICING_RECORD_TATTOO, $msg->fopGroup[0]->pnrElementAssociation[0]->referenceDetails->type);
        $this->assertEquals(2, $msg->fopGroup[0]->pnrElementAssociation[1]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_SERVICE_PRICING_RECORD_TATTOO, $msg->fopGroup[0]->pnrElementAssociation[1]->referenceDetails->type);
        $this->assertEquals(1, $msg->fopGroup[0]->pnrElementAssociation[2]->referenceDetails->value);
        $this->assertEquals(ReferenceDetails::TYPE_SPECIAL_SERVICE_REQUEST, $msg->fopGroup[0]->pnrElementAssociation[2]->referenceDetails->type);

        $this->assertCount(1, $msg->fopGroup[0]->passengerAssociation);
        $this->assertEquals(PassengerReference::TYPE_ADULT, $msg->fopGroup[0]->passengerAssociation[0]->passengerReference->type);
        $this->assertEquals(1, $msg->fopGroup[0]->passengerAssociation[0]->passengerReference->value);

        $this->assertCount(3, $msg->fopGroup[0]->mopDescription);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('CASH', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule);

        //We should really assert the structure again. But it's redundant
    }

    /**
     * 5.19 Operation: OB Fees computation
     *
     * Query to trigger OB fee calculation with Pricing options.
     */
    public function testCanMakeMessageWithObFeesComputation()
    {
        $opt = new FopCreateFopOptions([
            'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
            'obFeeCalculation' => true,
            'fopGroup' => [
                new Group([
                    'obFeeComputation' => new ObFeeComputation([
                        'option' => ObFeeComputation::OPTION_OB_FEES,
                        'optionInformation' => ObFeeComputation::OPTIONINF_EXEMPT_ALL_OB_FEES,
                        'city' => 'NCE',
                        'departureDate' => \DateTime::createFromFormat('Ymd', '20140412')
                    ]),
                    'mopInfo' => [
                        new MopInfo([
                            'sequenceNr' => 1,
                            'fopCode' => 'CCVI',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'payMerchant' => 'AY',
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010016',
                                'expiryDate' => '0915'
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals(TransactionDetails::TRANS_AUTH_ON_TICKET_MCO_EMD, $msg->transactionContext->transactionDetails->code);
        $this->assertEquals('O', $msg->transactionContext->transactionDetails->issueIndicator);

        $this->assertEmpty( $msg->fopGroup[0]->pnrElementAssociation);
        $this->assertEmpty($msg->fopGroup[0]->passengerAssociation);

        $this->assertEquals('NCE', $msg->fopGroup[0]->pricingTicketingDetails->locationDetails->city);
        $this->assertEquals('120414', $msg->fopGroup[0]->pricingTicketingDetails->productDateTimeDetails->departureDate);

        $this->assertEquals('OB', $msg->fopGroup[0]->feeTypeInfo->selectionDetails->option);
        $this->assertEquals('EX', $msg->fopGroup[0]->feeTypeInfo->selectionDetails->optionInformation);

        $this->assertEquals(1, $msg->fopGroup[0]->mopDescription[0]->fopSequenceNumber->sequenceDetails->number);
        $this->assertEquals('CCVI', $msg->fopGroup[0]->mopDescription[0]->mopDetails->fopPNRDetails->fopDetails[0]->fopCode);
        $this->assertEquals('CC', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->fopInformation->formOfPayment->type);

        $this->assertEquals('AY', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->merchantInformation->companyCode);

        $this->assertEquals('VI', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->vendorCode);
        $this->assertEquals('4541099100010016', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->cardNumber);
        $this->assertEquals('0915', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->ccHolderName);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->securityId);

        $this->assertEquals('0915', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData);
    }

    public function testCanMakeMessageWithBestEffortIndicator()
    {
        $opt = new FopCreateFopOptions([
            'transactionCode' => FopCreateFopOptions::TRANS_AUTH_ON_TICKET_MCO_EMD,
            'bestEffortIndicator' => FopCreateFopOptions::BESTEFFORT_IND_CREATE_FOP_IF_AUTH_FAILS,
            'bestEffortAction' => FopCreateFopOptions::BESTEFFORT_ACT_CONFIRM,
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
                            'payMerchant' => 'AY',
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'VI',
                                'cardNumber' => '4541099100010016',
                                'expiryDate' => '0915'
                            ])
                        ]),
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment($opt);

        $this->assertEquals(TransactionDetails::TRANS_AUTH_ON_TICKET_MCO_EMD, $msg->transactionContext->transactionDetails->code);
        $this->assertNull($msg->transactionContext->transactionDetails->issueIndicator);

        $this->assertCount(1, $msg->fopGroup[0]->pnrElementAssociation);
        $this->assertEmpty($msg->fopGroup[0]->passengerAssociation);

        $this->assertCount(1, $msg->bestEffort);
        $this->assertCount(1, $msg->bestEffort[0]->statusInformation);
        $this->assertEquals(\Amadeus\Client\Struct\Fop\CreateFormOfPayment\StatusInformation::ACTION_CONFIRMATION, $msg->bestEffort[0]->statusInformation[0]->action);
        $this->assertEquals(\Amadeus\Client\Struct\Fop\CreateFormOfPayment\StatusInformation::IND_CREATE_FOP_EVEN_IF_AUTHORIZATION_FAILED, $msg->bestEffort[0]->statusInformation[0]->indicator);

        $this->assertCount(1, $msg->fopGroup);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);
    }
}
