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

use Amadeus\Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions;
use Amadeus\Client\Struct\SalesReports\DisplayNetRemitReport;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\TransactionData;
use Test\Amadeus\BaseTestCase;

/**
 * DisplayNetRemitReportTest
 *
 * @package Test\Amadeus\Client\Struct\SalesReports
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class DisplayNetRemitReportTest extends BaseTestCase
{
    public function testCanMakeMessageEmpty()
    {
        $msg = new DisplayNetRemitReport(new SalesReportsDisplayNetRemitReportOptions());

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
        $this->assertNull($msg->documentInfo);
        $this->assertEmpty($msg->transactionTypeCodeInfo);
    }

    public function testCanMakeMessageWithTransactionTypeCodeInfo()
    {
        $code = 'TKTT';
        $type = 'AUTS';
        $issueIndicator = 'C';

        $opt = new SalesReportsDisplayNetRemitReportOptions([
            'transactionCode' => $code,
            'transactionType' => $type,
            'transactionIssueIndicator' => $issueIndicator,
        ]);

        $msg = new DisplayNetRemitReport($opt);

        $expectedTransactionTypeCodeInfo = new TransactionData($type, $code, $issueIndicator);
        $this->assertArraySubset([$expectedTransactionTypeCodeInfo], $msg->transactionTypeCodeInfo);
    }

    public function testCanMakeMessageWithDocumentInfo()
    {
        $documentInfo = SalesReportsDisplayNetRemitReportOptions::SALESIND_DOMESTIC;

        $opt = new SalesReportsDisplayNetRemitReportOptions([
            'documentInfo' => SalesReportsDisplayNetRemitReportOptions::SALESIND_DOMESTIC,
        ]);

        $msg = new DisplayNetRemitReport($opt);

        $this->assertInstanceOf('\Amadeus\Client\Struct\SalesReports\DisplayQueryReport\SalesIndicator', $msg->documentInfo);
        $this->assertEquals($documentInfo, $msg->documentInfo->statusInformation->type);
    }
}
