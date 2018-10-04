<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\SalesReports;

use Amadeus\Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions;
use Amadeus\Client\Struct\SalesReports\DisplayNetRemitReport;
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
        $this->assertNull($msg->salesIndicator);
        $this->assertEmpty($msg->transactionData);
    }
}
