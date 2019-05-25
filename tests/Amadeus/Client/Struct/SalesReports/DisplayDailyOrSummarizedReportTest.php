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

use Amadeus\Client\RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions;
use Amadeus\Client\Struct\SalesReports\DisplayDailyOrSummarizedReport;
use Amadeus\Client\Struct\SalesReports\DisplayDailyOrSummarizedReport\ItemNumberDetails;
use Test\Amadeus\BaseTestCase;

/**
 * DisplayDailyOrSummarizedReportTest
 *
 * @package Test\Amadeus\Client\Struct\SalesReports
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class DisplayDailyOrSummarizedReportTest extends BaseTestCase
{
    public function testCanMakeMessageEmpty()
    {
        $msg = new DisplayDailyOrSummarizedReport(new SalesReportsDisplayDailyOrSummarizedReportOptions());

        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->agencyDetails);
        $this->assertNull($msg->agentUserDetails);
        $this->assertNull($msg->currencyInfo);
        $this->assertNull($msg->dateDetails);
        $this->assertNull($msg->requestOption);
        $this->assertNull($msg->salesPeriodDetails);
        $this->assertNull($msg->salesIndicator);
        $this->assertNull($msg->salesReportIdentification);
        $this->assertNull($msg->dummy);
    }

    public function testCanMakeMessageWithSalesReportIdentification()
    {
        $number = 197;
        $type = SalesReportsDisplayDailyOrSummarizedReportOptions::SALES_REPORT_IDENTIFICATION_TYPE_NUMBER;

        $opt = new SalesReportsDisplayDailyOrSummarizedReportOptions([
            'salesReportIdentificationNumber' => $number,
            'salesReportIdentificationType' => $type,
        ]);

        $msg = new DisplayDailyOrSummarizedReport($opt);

        $expectedSalesReportIdentificationOption = new ItemNumberDetails($number, $type);
        $this->assertArraySubset([$expectedSalesReportIdentificationOption], $msg->salesReportIdentification->itemNumberDetails);
    }

    public function testCanMakeMessageWithCurrency()
    {
        $type = SalesReportsDisplayDailyOrSummarizedReportOptions::CURRENCY_TARGET;
        $currency = 'GBP';

        $opt = new SalesReportsDisplayDailyOrSummarizedReportOptions([
            'currencyType' => $type,
            'currency' => $currency,
        ]);

        $msg = new DisplayDailyOrSummarizedReport($opt);

        $this->assertInstanceOf('\Amadeus\Client\Struct\SalesReports\DisplayQueryReport\CurrencyInfo', $msg->currency);
        $this->assertEquals($type, $msg->currency->currencyDetails->currencyQualifier);
        $this->assertEquals($currency, $msg->currency->currencyDetails->currencyIsoCode);
    }
}
