<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
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
