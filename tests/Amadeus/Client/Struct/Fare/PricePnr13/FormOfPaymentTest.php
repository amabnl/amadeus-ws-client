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

namespace Test\Amadeus\Client\Struct\Fare\PricePnr13;

use Amadeus\Client\Struct\Fare\PricePnr13\FormOfPayment;
use Test\Amadeus\BaseTestCase;

/**
 * FormOfPaymentTest
 *
 * @package Test\Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FormOfPaymentTest extends BaseTestCase
{
    public function testCanConstruct()
    {
        $fop = new FormOfPayment(FormOfPayment::TYPE_CASH);

        $this->assertEquals(FormOfPayment::TYPE_CASH, $fop->type);

        $this->assertNull($fop->addressVerification);
        $this->assertNull($fop->amount);
        $this->assertNull($fop->approvalCode);
        $this->assertNull($fop->authorisedAmount);
        $this->assertNull($fop->creditCardNumber);
        $this->assertNull($fop->customerAccount);
        $this->assertNull($fop->expiryDate);
        $this->assertNull($fop->extendedPayment);
        $this->assertNull($fop->approvalCode);
        $this->assertNull($fop->fopFreeText);
        $this->assertNull($fop->indicator);
        $this->assertNull($fop->membershipStatus);
        $this->assertNull($fop->pinCode);
        $this->assertNull($fop->pinCodeType);
        $this->assertNull($fop->sourceOfApproval);
        $this->assertNull($fop->vendorCode);
    }
}
