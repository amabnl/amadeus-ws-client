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

use Amadeus\Client\RequestOptions\Fop\InstallmentsInfo;
use Amadeus\Client\Struct\Fop\ExtendedPaymentDetails;
use Test\Amadeus\BaseTestCase;

/**
 * ExtendedPaymentDetailsTest
 *
 * @package Test\Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ExtendedPaymentDetailsTest extends BaseTestCase
{
    public function testCanLoadFormat105()
    {
        $obj = new ExtendedPaymentDetails(
            new InstallmentsInfo([
                'nrOfInstallments' => 2,
                'frequency' => InstallmentsInfo::FREQUENCY_MONTHLY,
                'startDate' => \DateTime::createFromFormat('Ymd', '20130223'),
                'format' => InstallmentsInfo::FORMAT_YYDDD
            ])
        );

        $this->assertEquals('13053', $obj->instalmentsStartDate);
    }

    public function testCanLoadFormat106()
    {
        $obj = new ExtendedPaymentDetails(
            new InstallmentsInfo([
                'nrOfInstallments' => 2,
                'frequency' => InstallmentsInfo::FREQUENCY_MONTHLY,
                'startDate' => \DateTime::createFromFormat('Ymd', '20130223'),
                'format' => InstallmentsInfo::FORMAT_MMDD
            ])
        );

        $this->assertEquals('0223', $obj->instalmentsStartDate);
    }

    public function testCanLoadHandleLoadInvalidFormatFormat106()
    {

        $this->setExpectedException('\RuntimeException', "Installments Format 'invalid' is not implemented!");

        new ExtendedPaymentDetails(
            new InstallmentsInfo([
                'nrOfInstallments' => 2,
                'frequency' => InstallmentsInfo::FREQUENCY_MONTHLY,
                'startDate' => \DateTime::createFromFormat('Ymd', '20130223'),
                'format' => 'invalid'
            ])
        );
    }
}
