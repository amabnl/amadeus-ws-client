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

namespace Test\Amadeus\Client\Struct\SalesReports;

use Amadeus\Client\RequestOptions\SalesReportsDisplayQueryReportOptions;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport;
use Test\Amadeus\BaseTestCase;

/**
 * DisplayQueryReportTest
 *
 * @package Test\Amadeus\Client\Struct\SalesReports
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DisplayQueryReportTest extends BaseTestCase
{
    public function testCanMakeMessageEmpty()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions());

        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->agencyDetails);
        $this->assertNull($msg->agentUserDetails);
        $this->assertNull($msg->attributeInfo);
        $this->assertNull($msg->currencyInfo);
        $this->assertNull($msg->dateDetails);
        $this->assertNull($msg->formOfPaymentDetails);
        $this->assertNull($msg->fromSequenceDocumentNumber);
        $this->assertNull($msg->requestOption);
        $this->assertNull($msg->salesPeriodDetails);
        $this->assertNull($msg->salesIndicator);
        $this->assertEmpty($msg->transactionData);
    }

    public function testCanMakeMessageRequestOptions()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'requestOptions' => [
                SalesReportsDisplayQueryReportOptions::SELECT_OFFICE_ALL_AGENTS,
                SalesReportsDisplayQueryReportOptions::SELECT_SATELLITE_TICKET_OFFICE
            ]
        ]));

        $this->assertEquals(
            DisplayQueryReport\SelectionDetails::SELECT_OFFICE_ALL_AGENTS,
            $msg->requestOption->selectionDetails->option
        );
        $this->assertCount(1, $msg->requestOption->otherSelectionDetails);
        $this->assertEquals(
            DisplayQueryReport\SelectionDetails::SELECT_SATELLITE_TICKET_OFFICE,
            $msg->requestOption->otherSelectionDetails[0]->option
        );
    }

    public function testCanMakeMessageOfficesSharingSameIataCode()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'requestOptions' => [
                SalesReportsDisplayQueryReportOptions::SELECT_ALL_OFFICES_SHARING_IATA_NR
            ],
            'agencySourceType' => SalesReportsDisplayQueryReportOptions::AGENCY_SRC_REPORTING_OFFICE,
            'agencyIataNumber' => '23491193'
        ]));

        $this->assertEquals(
            SalesReportsDisplayQueryReportOptions::SELECT_ALL_OFFICES_SHARING_IATA_NR,
            $msg->requestOption->selectionDetails->option
        );
        $this->assertEmpty($msg->requestOption->otherSelectionDetails);

        $this->assertEquals(
            SalesReportsDisplayQueryReportOptions::AGENCY_SRC_REPORTING_OFFICE,
            $msg->agencyDetails->sourceType->sourceQualifier1
        );
        $this->assertEquals(
            '23491193',
            $msg->agencyDetails->originatorDetails->originatorId
        );
        $this->assertEmpty($msg->agencyDetails->originatorDetails->inHouseIdentification1);
    }

    /**
     * https://github.com/amabnl/amadeus-ws-client/issues/59
     */
    public function testCanMakeMessageOfficesSharingSameIataCodeWithZeroStart()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'requestOptions' => [
                SalesReportsDisplayQueryReportOptions::SELECT_ALL_OFFICES_SHARING_IATA_NR
            ],
            'agencySourceType' => SalesReportsDisplayQueryReportOptions::AGENCY_SRC_REPORTING_OFFICE,
            'agencyIataNumber' => '03430953'
        ]));

        $this->assertEquals(
            SalesReportsDisplayQueryReportOptions::SELECT_ALL_OFFICES_SHARING_IATA_NR,
            $msg->requestOption->selectionDetails->option
        );
        $this->assertEmpty($msg->requestOption->otherSelectionDetails);

        $this->assertEquals(
            SalesReportsDisplayQueryReportOptions::AGENCY_SRC_REPORTING_OFFICE,
            $msg->agencyDetails->sourceType->sourceQualifier1
        );
        $this->assertEquals(
            '03430953',
            $msg->agencyDetails->originatorDetails->originatorId
        );
        $this->assertEmpty($msg->agencyDetails->originatorDetails->inHouseIdentification1);
    }

    public function testCanMakeMessageIssuedForSpecificAgent()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'agentCode' => '1111AA'
        ]));

        $this->assertEquals('1111AA', $msg->agentUserDetails->originIdentification->originatorId);
    }

    public function testCanMakeMessageAllAgentsForSpecificOffice()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'requestOptions' => [
                SalesReportsDisplayQueryReportOptions::SELECT_OFFICE_ALL_AGENTS
            ],
            'agencySourceType' => SalesReportsDisplayQueryReportOptions::AGENCY_SRC_REPORTING_OFFICE,
            'agencyIataNumber' => '23491193',
            'agencyOfficeId' => 'FRA6X098F'
        ]));

        $this->assertEquals(
            SalesReportsDisplayQueryReportOptions::SELECT_OFFICE_ALL_AGENTS,
            $msg->requestOption->selectionDetails->option
        );
        $this->assertEmpty($msg->requestOption->otherSelectionDetails);

        $this->assertEquals(
            SalesReportsDisplayQueryReportOptions::AGENCY_SRC_REPORTING_OFFICE,
            $msg->agencyDetails->sourceType->sourceQualifier1
        );
        $this->assertEquals(
            '23491193',
            $msg->agencyDetails->originatorDetails->originatorId
        );
        $this->assertEquals('FRA6X098F', $msg->agencyDetails->originatorDetails->inHouseIdentification1);
    }

    public function testCanMakeMessageTransactionCode()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'transactionCode' => 'TKTT'
        ]));

        $this->assertCount(1, $msg->transactionData);
        $this->assertEquals('TKTT', $msg->transactionData[0]->transactionDetails->code);
        $this->assertNull($msg->transactionData[0]->transactionDetails->type);
        $this->assertNull($msg->transactionData[0]->transactionDetails->issueIndicator);
    }

    public function testCanMakeMessageTransactionType()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'transactionType' => 'SALE',
            'transactionIssueIndicator' => 'C'
        ]));

        $this->assertNull($msg->transactionData[0]->transactionDetails->code);
        $this->assertEquals('SALE', $msg->transactionData[0]->transactionDetails->type);
        $this->assertEquals('C', $msg->transactionData[0]->transactionDetails->issueIndicator);
    }

    public function testCanMakeMessageValidatingCarrier()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'validatingCarrier' => '6X'
        ]));

        $this->assertEquals('6X', $msg->validatingCarrierDetails->companyIdentification->marketingCompany);
    }

    public function testCanMakeMessageFopCreditCardVendor()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'fopType' => SalesReportsDisplayQueryReportOptions::FOP_CREDIT_CARD,
            'fopVendor' => 'AX'
        ]));

        $this->assertEquals(
            DisplayQueryReport\FormOfPayment::FOP_CREDIT_CARD,
            $msg->formOfPaymentDetails->formOfPayment->type
        );
        $this->assertEquals('AX', $msg->formOfPaymentDetails->formOfPayment->vendorCode);
    }

    public function testCanMakeMessageWithSalesIndicator()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'salesIndicator' => SalesReportsDisplayQueryReportOptions::SALESIND_DOMESTIC
        ]));

        $this->assertEquals(
            DisplayQueryReport\StatusInformation::SALESIND_DOMESTIC,
            $msg->salesIndicator->statusInformation->type
        );
    }

    public function testCanMakeMessageCurrencyCode()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'currencyType' => SalesReportsDisplayQueryReportOptions::CURRENCY_TARGET,
            'currency' => 'USD'
        ]));

        $this->assertEquals('USD', $msg->currencyInfo->currencyDetails->currencyIsoCode);
        $this->assertEquals(
            DisplayQueryReport\CurrencyDetails::CURRENCY_TARGET,
            $msg->currencyInfo->currencyDetails->currencyQualifier
        );
    }

    public function testCanMakeMessageForSpecificDate()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'specificDate' => \DateTime::createFromFormat('YmdHis', '20161023000000', new \DateTimeZone('UTC')),
            'specificDateType' => SalesReportsDisplayQueryReportOptions::DATE_TYPE_SPECIFIC
        ]));

        $this->assertEquals(
            DisplayQueryReport\DateDetails::DATE_TYPE_SPECIFIC,
            $msg->dateDetails->businessSemantic
        );
        $this->assertEquals('2016', $msg->dateDetails->dateTime->year);
        $this->assertEquals('10', $msg->dateDetails->dateTime->month);
        $this->assertEquals('23', $msg->dateDetails->dateTime->day);
    }

    public function testCanMakeMessageSalesPeriod()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'startDate' => \DateTime::createFromFormat('YmdHis', '20150101000000', new \DateTimeZone('UTC')),
            'endDate' => \DateTime::createFromFormat('YmdHis', '20160331000000', new \DateTimeZone('UTC'))
        ]));

        $this->assertEquals('2015', $msg->salesPeriodDetails->beginDateTime->year);
        $this->assertEquals('01', $msg->salesPeriodDetails->beginDateTime->month);
        $this->assertEquals('01', $msg->salesPeriodDetails->beginDateTime->day);
        $this->assertEquals('2016', $msg->salesPeriodDetails->endDateTime->year);
        $this->assertEquals('03', $msg->salesPeriodDetails->endDateTime->month);
        $this->assertEquals('31', $msg->salesPeriodDetails->endDateTime->day);
    }

    public function testCanMakeMessageScrollingLastFiftyRows()
    {
        $msg = new DisplayQueryReport(new SalesReportsDisplayQueryReportOptions([
            'scrollingCount' => 50,
            'scrollingFromItem' => '4527896352'
        ]));

        $this->assertEquals(50, $msg->actionDetails->numberOfItemsDetails->numberOfItems);
        $this->assertEquals('4527896352', $msg->actionDetails->lastItemsDetails->lastItemIdentifier);
    }
}
