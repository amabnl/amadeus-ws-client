<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\SalesReports;

use Amadeus\Client\RequestOptions\SalesReportsDisplayDailyorSummarizedReportOptions;
use Amadeus\Client\Struct\SalesReports\DisplayDailyorSummarizedReport;
use Amadeus\Client\Struct\SalesReports\DisplayDailyorSummarizedReport\ItemNumberDetails;
use Test\Amadeus\BaseTestCase;

/**
 * DisplayDailyorSummarizedReportTest
 *
 * @package Test\Amadeus\Client\Struct\SalesReports
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class DisplayDailyorSummarizedReportTest extends BaseTestCase
{
    public function testCanMakeMessageEmpty()
    {
        $msg = new DisplayDailyorSummarizedReport(new SalesReportsDisplayDailyorSummarizedReportOptions());

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
        $type = SalesReportsDisplayDailyorSummarizedReportOptions::SALES_REPORT_IDENTIFICATION_TYPE_NUMBER;

        $opt = new SalesReportsDisplayDailyorSummarizedReportOptions([
            'salesReportIdentificationNumber' => $number,
            'salesReportIdentificationType' => $type,
        ]);

        $msg = new DisplayDailyorSummarizedReport($opt);

        $expectedSalesReportIdentificationOption = new ItemNumberDetails($number, $type);
        $this->assertArraySubset([$expectedSalesReportIdentificationOption], $msg->salesReportIdentification->itemNumberDetails);
    }

    public function testCanMakeMessageWithCurrency()
    {
        $type = SalesReportsDisplayDailyorSummarizedReportOptions::CURRENCY_TARGET;
        $currency = 'GBP';

        $opt = new SalesReportsDisplayDailyorSummarizedReportOptions([
            'currencyType' => $type,
            'currency' => $currency,
        ]);

        $msg = new DisplayDailyorSummarizedReport($opt);

        $this->assertInstanceOf('\Amadeus\Client\Struct\SalesReports\DisplayQueryReport\CurrencyInfo', $msg->currency);
        $this->assertEquals($type, $msg->currency->currencyDetails->currencyQualifier);
        $this->assertEquals($currency, $msg->currency->currencyDetails->currencyIsoCode);
    }
}
