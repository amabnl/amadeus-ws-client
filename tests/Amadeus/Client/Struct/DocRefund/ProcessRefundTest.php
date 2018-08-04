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

namespace Test\Amadeus\Client\Struct\DocRefund;

use Amadeus\Client\RequestOptions\DocRefund\RefundItinOpt;
use Amadeus\Client\RequestOptions\DocRefundProcessRefundOptions;
use Amadeus\Client\Struct\DocRefund\ProcessRefund;
use Amadeus\Client\Struct\DocRefund\StatusDetails;
use Amadeus\Client\Struct\DocRefund\UpdateRefund\ReferenceDetails;
use Test\Amadeus\BaseTestCase;

/**
 * ProcessRefundTest
 *
 * @package Test\Amadeus\Client\Struct\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ProcessRefundTest extends BaseTestCase
{
    /**
     * 5.1 Operation: Inhibit the refund notice print
     *
     * This operation inhibits the printing of the refund notice.
     */
    public function testCanMakeMessageInhibitRefundNotice()
    {
        $opt = new DocRefundProcessRefundOptions([
            'statusIndicators' => [DocRefundProcessRefundOptions::STATUS_INHIBIT_REFUND_NOTICE]
        ]);

        $msg = new ProcessRefund($opt);

        $this->assertEquals(StatusDetails::INDICATOR_INHIBIT_REFUND_NOTICE, $msg->actionDetails->statusDetails->indicator);
        $this->assertEmpty($msg->actionDetails->otherDetails);

        $this->assertEmpty($msg->phoneFaxEmailAddress);
        $this->assertNull($msg->printerReference);
        $this->assertEmpty($msg->refundedItinerary);
        $this->assertNull($msg->dummysegment);
        $this->assertNull($msg->targetStockProvider);
    }

    /**
     * 5.2 Operation: Print refund notice on specific printer
     *
     * This operation sends an update to the refund application by specifying a printer mnemonic to print to.
     */
    public function testCanMakeMessageSpecifyPrinter()
    {
        $opt = new DocRefundProcessRefundOptions([
            'printerType' => DocRefundProcessRefundOptions::PRINTERTYPE_PRINTER_MNEMONIC,
            'printer' => 'D00030'
        ]);

        $msg = new ProcessRefund($opt);

        $this->assertNull($msg->actionDetails);
        $this->assertEmpty($msg->phoneFaxEmailAddress);
        $this->assertEmpty($msg->refundedItinerary);
        $this->assertNull($msg->dummysegment);
        $this->assertNull($msg->targetStockProvider);

        $this->assertCount(1, $msg->printerReference->referenceDetails);
        $this->assertEquals(ReferenceDetails::TYPE_PRINTER_MNEMONIC, $msg->printerReference->referenceDetails[0]->type);
        $this->assertEquals('D00030', $msg->printerReference->referenceDetails[0]->value);
    }

    /**
     * 5.4 Operation: Process refund adding refunded itinerary
     *
     * This operation adds a refunded itinerary to the refund record.
     */
    public function testCanMakeMessageSpecifyItinerary()
    {
        $opt = new DocRefundProcessRefundOptions([
            'refundedItinerary' => [
                new RefundItinOpt([
                    'company' => 'AF',
                    'origin' => 'NCE',
                    'destination' => 'PAR',
                ])
            ]
        ]);

        $msg = new ProcessRefund($opt);

        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->printerReference);
        $this->assertEmpty($msg->phoneFaxEmailAddress);
        $this->assertNull($msg->dummysegment);
        $this->assertNull($msg->targetStockProvider);

        $this->assertCount(1, $msg->refundedItinerary);
        $this->assertEquals('AF', $msg->refundedItinerary[0]->airlineCodeRfndItinerary->companyIdentification->operatingCompany);
        $this->assertEquals('NCE', $msg->refundedItinerary[0]->originDestinationRfndItinerary->origin);
        $this->assertEquals('PAR', $msg->refundedItinerary[0]->originDestinationRfndItinerary->destination);
    }

    /**
     * 5.7 Operation: Send refund notice to email address stored in the PNR
     *
     * This operation sends the refund notice at commit or resend time to
     * the e-mail address stored in the PNR element APE.
     *
     * The system only checks for elements associated to the refunded passenger.
     */
    public function testCanMakeMessageEmailToApe()
    {
        $opt = new DocRefundProcessRefundOptions([
            'sendNotificationToEmailInAPE' => true
        ]);

        $msg = new ProcessRefund($opt);

        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->printerReference);
        $this->assertNull($msg->dummysegment);
        $this->assertNull($msg->targetStockProvider);
        $this->assertEmpty($msg->refundedItinerary);

        $this->assertCount(1, $msg->phoneFaxEmailAddress);
        $this->assertEquals(ProcessRefund\PhoneFaxEmailAddress::TYPE_E_MAIL_ADDRESS_FROM_APE, $msg->phoneFaxEmailAddress[0]->phoneOrEmailType);
        $this->assertNull($msg->phoneFaxEmailAddress[0]->emailAddress);
        $this->assertNull($msg->phoneFaxEmailAddress[0]->telephoneNumberDetails);
    }

    /**
     * 5.8 Operation: Send refund notice to email adresses
     *
     * This operation sends the refund notice at commit or reprint time
     * to one or several email addresses specified in the request.
     *
     * Up to three addresses can be specified, using the phoneFaxEmailAddress element.
     */
    public function testCanMakeMessageEmailSpecific()
    {
        $opt = new DocRefundProcessRefundOptions([
            'refundNoticesEmailAddresses' => [
                'username@mailbox.com',
                'username2@mailbox.com',
            ]
        ]);

        $msg = new ProcessRefund($opt);

        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->printerReference);
        $this->assertNull($msg->dummysegment);
        $this->assertNull($msg->targetStockProvider);
        $this->assertEmpty($msg->refundedItinerary);

        $this->assertCount(2, $msg->phoneFaxEmailAddress);
        $this->assertEquals(ProcessRefund\PhoneFaxEmailAddress::TYPE_E_MAIL_ADDRESS, $msg->phoneFaxEmailAddress[0]->phoneOrEmailType);
        $this->assertEquals('username@mailbox.com', $msg->phoneFaxEmailAddress[0]->emailAddress);
        $this->assertNull($msg->phoneFaxEmailAddress[0]->telephoneNumberDetails);

        $this->assertEquals(ProcessRefund\PhoneFaxEmailAddress::TYPE_E_MAIL_ADDRESS, $msg->phoneFaxEmailAddress[1]->phoneOrEmailType);
        $this->assertEquals('username2@mailbox.com', $msg->phoneFaxEmailAddress[1]->emailAddress);
        $this->assertNull($msg->phoneFaxEmailAddress[1]->telephoneNumberDetails);
    }

    /**
     * 5.9 Operation: Send refund notice to fax number stored in the PNR
     *
     * This operation sends the refund notice at commit or resend time to the fax number stored in the PNR element APF.
     *
     * The system only checks for elements associated to the refunded passenger.
     */
    public function testCanMakeMessageFaxApf()
    {
        $opt = new DocRefundProcessRefundOptions([
            'sendNotificationToFaxInAPF' => true
        ]);

        $msg = new ProcessRefund($opt);

        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->printerReference);
        $this->assertNull($msg->dummysegment);
        $this->assertNull($msg->targetStockProvider);
        $this->assertEmpty( $msg->refundedItinerary);

        $this->assertCount(1, $msg->phoneFaxEmailAddress);
        $this->assertEquals(ProcessRefund\PhoneFaxEmailAddress::TYPE_FAX_NUMBER_FROM_APF, $msg->phoneFaxEmailAddress[0]->phoneOrEmailType);
        $this->assertNull($msg->phoneFaxEmailAddress[0]->emailAddress);
        $this->assertNull($msg->phoneFaxEmailAddress[0]->telephoneNumberDetails);
    }

    /**
     * 5.10 Operation: Send refund notice to fax numbers
     *
     * This operation sends the refund notice at commit or reprint time to
     * one or several fax numbers specified in the request.
     *
     * Up to three fax numbers can be specified, using the phoneFaxEmailAddress element.
     */
    public function testCanMakeMessageFaxSpecific()
    {
        $opt = new DocRefundProcessRefundOptions([
            'refundNoticesFaxes' => [
                '1234567' => 'FR',
                '9876541' => 'AU',
            ]
        ]);

        $msg = new ProcessRefund($opt);

        $this->assertNull($msg->actionDetails);
        $this->assertNull($msg->printerReference);
        $this->assertNull($msg->dummysegment);
        $this->assertNull($msg->targetStockProvider);
        $this->assertEmpty($msg->refundedItinerary);

        $this->assertCount(2, $msg->phoneFaxEmailAddress);
        $this->assertEquals(ProcessRefund\PhoneFaxEmailAddress::TYPE_FAX_NUMBER, $msg->phoneFaxEmailAddress[0]->phoneOrEmailType);
        $this->assertNull($msg->phoneFaxEmailAddress[0]->emailAddress);
        $this->assertEquals('FR', $msg->phoneFaxEmailAddress[0]->telephoneNumberDetails->areaCode);
        $this->assertEquals('1234567', $msg->phoneFaxEmailAddress[0]->telephoneNumberDetails->telephoneNumber);

        $this->assertEquals(ProcessRefund\PhoneFaxEmailAddress::TYPE_FAX_NUMBER, $msg->phoneFaxEmailAddress[1]->phoneOrEmailType);
        $this->assertNull($msg->phoneFaxEmailAddress[1]->emailAddress);
        $this->assertEquals('AU', $msg->phoneFaxEmailAddress[1]->telephoneNumberDetails->areaCode);
        $this->assertEquals('9876541', $msg->phoneFaxEmailAddress[1]->telephoneNumberDetails->telephoneNumber);
    }
}
