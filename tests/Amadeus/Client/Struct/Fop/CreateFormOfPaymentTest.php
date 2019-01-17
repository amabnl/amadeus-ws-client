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
use Amadeus\Client\RequestOptions\Fop\ThreeDSecureInfo;
use Amadeus\Client\RequestOptions\FopCreateFopOptions;
use Amadeus\Client\Struct\Fop\AttributeDetails;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment14;
use Amadeus\Client\Struct\Fop\DeviceIdentification;
use Amadeus\Client\Struct\Fop\FormOfPayment;
use Amadeus\Client\Struct\Fop\FreeTextDetails;
use Amadeus\Client\Struct\Fop\InvoiceFormOfPayment;
use Amadeus\Client\Struct\Fop\MonetaryDetails;
use Amadeus\Client\Struct\Fop\PassengerReference;
use Amadeus\Client\Struct\Fop\PaymentId;
use Amadeus\Client\Struct\Fop\ReferenceDetails;
use Amadeus\Client\Struct\Fop\StatusInformation;
use Amadeus\Client\Struct\Fop\TdsBlbData;
use Amadeus\Client\Struct\Fop\TdsReferenceDetails;
use Amadeus\Client\Struct\Fop\TransactionDetails;
use Test\Amadeus\BaseTestCase;

/**
 * CreateFormOfPaymentTest
 *
 * @package Test\Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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
                                'phone' => '(212) 664-7665',
                                'email' => 'test@test.com'
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

        $this->assertCount(1, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->formOfIdDetails);
        $this->assertEquals(ReferenceDetails::TYPE_CPF_BRAZILIAN_SECURITY_NUMBER, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->formOfIdDetails[0]->referenceDetails->type);
        $this->assertEquals('25208731592', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->formOfIdDetails[0]->referenceDetails->value);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->billingAddress);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->merchantURL);

        $this->assertCount(2, $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerPhoneOrEmail);
        $this->assertEquals('(212) 664-7665', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerPhoneOrEmail[0]->telephoneNumberDetails->telephoneNumber);
        $this->assertEquals('test@test.com', $msg->fopGroup[0]->mopDescription[0]->paymentModule->paymentData->fraudScreeningData->payerPhoneOrEmail[1]->emailAddress);

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

    /**
     * 5.2 Operation: Best effort
     *
     * Best effort process triggered.
     */
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

    /**
     * 5.1 Operation: 3DS payment
     *
     * Query and response for handling 3DS data.
     */
    public function testCanMakeMessageWith3dSecurePayment()
    {
        $pares = <<<EOT
ZUp6ZFdGbVBvMGdTZnU5ZlVhcDV0SG80aksdV3k2UGt2ZzhiYk1NYkJneVkyMkN1WDcvcGNuZFZ6V3ozcW1kblY5clpVaUVuWDBiR2tSSHhrYkQdcmMvU3B6YTQxbkdSdnp4anY2TFBUMEh1Rlg2Y2h5L1Bsc2wdWGo3L3R2bTBOcU5yRU5DN3dMdGRnODFhQ2VyYURZT24ySDk1MXNFMnFEN250OXByMjdKSh0vQjVzNzVqOWV1c3BNZjJXU01zVk03RjdjanZzRzRlUVlHdkJqZlEzcS80R3ZsMkN4VmZ2Y2pObTgzYTlTcFNVRGZFQWx1Z3N6WHk5WGFkQlZlQjNxeFdLTHFZcjVISDNScDVYNmJmN3FNYR10akgva1lqT3RRNk54bGZEald6aXdNbVZ5aEEyNk5tR2k5cjVDNng5dDBtMk9Bb05rVnhkUEdFb2w5ZS85ZklLNzR1Nx1wQVZ0eWdiZ3hGN3hNZm9UWGNqaXZjcm1Heldpelh5TnZkT3VqTElnHWdCQXp1YmJ4RzNyMHIzWHlEL3Y1dkJYVkRkRzBlTh1zbXpyNTZOVVdKSnd6N01sdDhJYUNGVjN4ZE4yNXpxemYyR3ZrNldudHUyMjRVR25RL3VHQzByeUxyd0lzMzZOMHAdUHU2Q3FSaGNZMmJLTnRnRDVsM1lJM2NYVUZlazdsWjcdSXdoOGF1d1JPc21MeB1lWTZhcHZ5Q0lGM1gvZHBOZnkydUlZTERLQkIwaFVBQnY0N0RYNTRmcXdKZnlNL0ZuMXBHdVhtUng1NmJ4cVBid01wUWdpWXEvS2MzMzc2bnh0emVOV0hJbHFFHVExV2ZQWXpJUDk4UmRJck5ucEVQRWZ5TXRqODZkYTNkejNYa1luZEYyHUFjM0JNZFBGbGI0ZVg1bHg4VU9SMkhRZDM4TzhhHUdYcG8yTHZwTGRpSWs3M05tVnYyYXFZOUp0U2FBZVlEVmpyTEtWdkE3SDZVWENOdkRzTHhld1kdN01CRFVQWnA4eUMzTno5QnJFeGRUVjB5c0U3bnNJc1p1NnFTcFNheDhZNFZMOXU5ZTF1bEhaTTFsQy9oc1g2MEN2WWNlZjN0RTNvcVNHTWVUMUpMUEVUT3VjSjFidDdLM2NBR2VPVmlSTk1OdDc0T0E4L25zR1RKN09jajJGWGNvdE11aB1RVUxRbnBVOEJFYzk5b2k5N2lkN2ttaGQ1ZUZqbWRIN0hiYlYvb3hHVnZKZ29lcTBWZWRTHVBVRDY0djVhQzRSSFhjWWF1YUxkeEh5TXF1RGJ4R1ZZUDdHQkZFT2pNcENqUVNTSG9CQktFZ2doMGRuTE9xa1FYNlVBQktFZEJwM2JDYVVvYkRFa2FGbEFFVGxXTXVxTU1tOTRiQnNkMEltZGRHRmtCQ1Fjd2k2RkloVkpvdTZkTklKT2h1aWVCWjVMTXJMUnhxMmRvb0Qyd3dpUlJ0ckVQMjlRL0tqMVBBL2VCMXlhRFF5ekdCb2RERzU5clV2bmdERFlzR3Z1WWZOQ3BtQ1JMUm9ISjlNSUlJakpNcWlpSnVWV0hrcFRCSEZnZXprSGRKMjZiR3R5Ym5adU5yeHFGSkk2MHljQx1ad2JGaE5lZDhmWUZ4SVE3MW12dldCZmFhRR1OUUh6WXRFMlFPTHNQc1F0TUozVHFoVkVWVUwvR1R2WUtZMmJweFRFWlF3SEVZejhpaFRjT1dPUndlOHdiR1U4aGl3ZmVLeWJFY1Brb0pxY2RocnE0MVRpNUdNbkgxN2plZEFLbzAdTFltWEpoTGdyRmZNeUhDTGEyNWNFNXFLZUdGN0MvMlJsbWx4T09kcUd6N0dqREZxWENFYUxXVThGckRnRU40N3JuVVFRRlJ3Sk5PazQ1SGQwR1BEa2NnOE53VW9FSR1NakRMaE90ZDVLYnRRMlVoZXNUUVdCanN1S1pwY3NBSTlWTEsdUEgzdHYxeXhVcEt1clJSVk5GWWhBUTZxVTVQVWRjZlRuRUpKdmhGZHFMMUdCZ0ZlbTB5V1RRcENGQlk0cFd6R3BVcXNaRHpHb28vQXFXNE1sTjlsUnZhZkdNT2hHSDBSdnJSdFRhWW9ZcTZmV1l1d0l0NnlCVVNBQzRTM2dtdXZEOE5ROWIxQ1NCMEFFYTFHU1lobEVTa2xFYlVjQkFHYjYzZlhOS3BxZFVOVTJLNUozRExQVXlGblVPUm1qZx04SG4wc3c5cUpIUFdSMy8wS1dScE0yd2dsTHQ5S2h4eEZHUmZUYWQ3cHBMbi9QakNnMk9jTi9DZXozd080VUpuUkR1OFNFSzdTQUpiUVBXQy9NejlmSlhhHU50UGF3Ti9sNGJWclpxZmZyUDFnZE9VUWJkbi9OWlZkS0RNVFdCHXJHUGdHR3g0TjZ1QmpBNWJjcnRZHU80RDA4VFoyc2Q2ZFIzOGFLYW91bEVUcTY0SDBlNVVPTEIxZ1pSdFY4Nmd6YTE2bE5PU2pkV2taTHVzajhXVnpmbXlKYXNXWmxNOFZoUkIvMGFFQU9WRTdTbVQycHppbTFYVy95bx1aUWZLMzFxVk5KMUtVM21mZ0tmZE5QRlJaenR1bGhobXJ4R2pZeXNSY1ByOGFGQXh6VEN5Tk81SERXaUxBbnhhUFRoQ3lUQ1A3TGM5MmlQeWkdUTlzTGdlN1RuLzREMnlKHWh2YjlLY2UvckljVmhNTjE3c1QxTmpmOEF6VEc5d3Y2UjVsNngveTJhTS85TE5DZTI4M0JINEpnbnd3ZTVaMGV0anM3M29aZDFIclZWcVNKZldIcmVaejZ1MnFYWFdJTkRrclA1Y1hHMHE2bWlPcGZnT3FJRUlVZ01JUGFJch1xbm02YUl6WFVsbERaL25WZ2FFUUcvd1NSUUM5SktrQzg3HUppVzVrYkRxHWhKQmxKaXVyT1pjN0dtempsRnNiWmQzVExlTUlObGZLSm4xTkk4aHZZHVFBeGRaUGZ5MFpEYWJ6UjNyd1VGZFBmOThabk9ZQlVBOGZNL3hjbx1ZbVhBQ1ZscHpuYmNwNnVWVkZwTTRLUGMyV1dwYW5rOU9uR1hxa002NDdVYkVjb2pQU3IxTGp5aG96SUMwaXBLY0UyMHJyMk5oS1NQbnE1TFV3d3kvTVdZaVRMaDRBdHM3UGxKbVNYVFFnOVZkbEpjQW1TWEphZ3poQVlYSWFuTkZ0Y0ZnHVN0aTdZbWhzZ1ZzY0lXYmpqSjhuT3VuL2FMNkVCcjU0SExaNWk0akJkNW1hWW84Wk50U3A5bzJLYk40WHR0NnYwZnRtbXZtbURRZnQdbTM3Qy8yZFBsWjA4ZndKdWxXU09rcHhNUVdzTVptcEpTM2NvVW5NTjJIUEtHc2psYTN1VlZ5bnZYUU51bjhybmRNZkVKZ0hZZUpScDhueWl2aGpNZExYUUp6HU5USVBSQXRKYTRFbWhNSkRLRHM1ekx1Z0lXNWx6TjI3bWEwV2QdbjAxbTJZRHdZcmgwUjBSZHFRaFFEdGZDbnBPTzBIdXAzVGxZZC9NdXR6bDFpYmJIUUxMbnF0LzR4alppMzA0ZlFkS0ZnZjM3RTRPcXhLUFZhbXE1SzEwWld4NjBvaGNYazBvOFR5NHdWdnp0eE9EQ2s0dDdBLzlLdnJBNXZoUTQ1dTlXeTJHWWdPVnhObkVyWTVtb1hVZURiNmN6cXdNZFF5TGpqMDRZMUlST0lsblYdWUc4MVpyRB0dSzF6Y0ltUDQxWjcvSXhDN21aZGtGS0hLUEJtdDZXQmIdU3FPbGlFb0hWMFY4MkpvSUZ6Rnd5allPOHo4dnd5bUFxcHRCRmRBSR1qHTd5VUo3cjZXcUdsd3VQTlNVZkNwNVQ1cFkzTEtMcEM1MUZ6OXdjMzBaelNyRldPTGFFMUxZNlUveXVtHVJqYm5IZDlsakFiRHUzc3h2eHFYUmJkTjg5WVNEdjcxakkyM3ZYHXh2WjYwZVkxNDlDOTg4R0h6OFdmZm9ISG5JRnpBPT0=
EOT;

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
                            'fopCode' => 'CCCA',
                            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
                            'payMerchant' => 'AY',
                            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
                            'creditCardInfo' => new CreditCardInfo([
                                'vendorCode' => 'CA',
                                'cardNumber' => '5000000000000009',
                                'expiryDate' => '1014',
                                'securityId' => 123,
                                'threeDSecure' => new ThreeDSecureInfo([
                                    'veresStatus' => ThreeDSecureInfo::VERES_AUTHENTICATION_AVAILABLE,
                                    'creditCardCompany' => ThreeDSecureInfo::CC_COMP_MASTERCARD_DIRECTORY_SERVER,
                                    'transactionIdentifier' => 'TzR3MFVmdG1IcHlzRVNpZUVuTUNBRFl6T1RRPQ==',
                                    'transactionIdentifierLength' => 28, //OPTIONAL, CAN BE OMITTED
                                    'paresAuthResponse' => $pares
                                ])
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

        $this->assertEmpty($msg->bestEffort);
        $this->assertCount(1, $msg->fopGroup);
        $this->assertCount(1, $msg->fopGroup[0]->mopDescription);

        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->authorisationSupplementaryData);

        $this->assertEquals('Y', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->authenticationData->authenticationDataDetails->veres);
        $this->assertEquals('CADS', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->authenticationData->authenticationDataDetails->creditCardCompany);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->authenticationData->authenticationDataDetails->pares);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->authenticationData->authenticationDataDetails->authenticationIndicator);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->authenticationData->authenticationDataDetails->caavAlgorithm);

        $this->assertCount(2, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData);
        $this->assertEquals(TdsReferenceDetails::REF_THREEDS_TRANSACTION_IDENTIFIER, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[0]->tdsBlbIdentifier->referenceDetails->value);
        $this->assertEquals('TzR3MFVmdG1IcHlzRVNpZUVuTUNBRFl6T1RRPQ==', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[0]->tdsBlbData->binaryData);
        $this->assertEquals(TdsBlbData::DATATYPE_BINARY, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[0]->tdsBlbData->dataType);
        $this->assertEquals(28, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[0]->tdsBlbData->dataLength);

        $this->assertEquals(TdsReferenceDetails::REF_PARES, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[1]->tdsBlbIdentifier->referenceDetails->value);
        $this->assertEquals($pares, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[1]->tdsBlbData->binaryData);
        $this->assertEquals(TdsBlbData::DATATYPE_BINARY, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[1]->tdsBlbData->dataType);
        $this->assertEquals(2996, $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopDetailedData->creditCardDetailedData->tdsInformation->tdsBlobData[1]->tdsBlbData->dataLength);
    }

    public function testCreateFopMessageGithubIssue163()
    {
        $options = new FopCreateFopOptions([
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
                                'expiryDate' => '0818',
                                'securityId' => 123,
                                'approvalCode' => '123456',
                                'sourceOfApproval' => CreditCardInfo::APPROVAL_SOURCE_MANUAL,
                                'name' => 'dummy name'
                            ])
                        ])
                    ]
                ])
            ]
        ]);

        $msg = new CreateFormOfPayment14($options);

        $this->assertInstanceOf('\Amadeus\Client\Struct\FOP\CreateFormOfPayment\FopGroup14', $msg->fopGroup[0]);
        $this->assertInstanceOf('\Amadeus\Client\Struct\FOP\CreateFormOfPayment\MopDescription14', $msg->fopGroup[0]->mopDescription[0]);
        $this->assertInstanceOf('\Amadeus\Client\Struct\FOP\CreateFormOfPayment\PaymentModule14', $msg->fopGroup[0]->mopDescription[0]->paymentModule);
        $this->assertInstanceOf('\Amadeus\Client\Struct\FOP\CreateFormOfPayment\GroupUsage14', $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage);
        $this->assertInstanceOf('\Amadeus\Client\Struct\FOP\AttributeDetails', $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails);
        $this->assertNull($msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails->attributeDescription);
        $this->assertEquals('FP', $msg->fopGroup[0]->mopDescription[0]->paymentModule->groupUsage->attributeDetails->attributeType);
        $this->assertEquals('0818', $msg->fopGroup[0]->mopDescription[0]->paymentModule->mopInformation->creditCardData->creditCardDetails->ccInfo->expiryDate);
    }
}
