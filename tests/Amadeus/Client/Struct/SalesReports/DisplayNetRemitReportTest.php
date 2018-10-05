<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
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
