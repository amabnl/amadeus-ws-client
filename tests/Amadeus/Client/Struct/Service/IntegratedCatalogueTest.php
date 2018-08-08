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

namespace Test\Amadeus\Client\Struct\Service;

use Amadeus\Client\RequestOptions\Service\PaxSegRef;
use Amadeus\Client\RequestOptions\ServiceIntegratedCatalogueOptions;
use Amadeus\Client\Struct\Service\IntegratedCatalogue;
use Amadeus\Client\Struct\Service\IntegratedPricing;
use Amadeus\Client\Struct\Fare\PricePnr13\ReferenceDetails;
use Test\Amadeus\BaseTestCase;

/**
 * IntegratedCatalogueTest
 *
 * @package Test\Amadeus\Client\Struct\Service
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IntegratedCatalogueTest extends BaseTestCase
{
    public function testCanMakeMessageWithAccountCode()
    {
        $opt = new ServiceIntegratedCatalogueOptions([
            'accountCode' => 'AAA123456',
            'accountCodeRefs' => [
                new PaxSegRef([
                    'type' => PaxSegRef::TYPE_PASSENGER,
                    'reference' => 1
                ])
            ]
        ]);

        $msg = new IntegratedCatalogue($opt);

        $this->assertCount(1, $msg->pricingOption);

        $this->assertEquals(IntegratedPricing\PricingOptionKey::OVERRIDE_ACCOUNT_CODE, $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertCount(1, $msg->pricingOption[0]->optionDetail->criteriaDetails);
        $this->assertEquals('AAA123456', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertNull($msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeDescription);

        $this->assertCount(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails);
        $this->assertEquals(1, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals(ReferenceDetails::QUALIFIER_PAX_REFERENCE, $msg->pricingOption[0]->paxSegTstReference->referenceDetails[0]->type);

        $this->assertNull($msg->pricingOption[0]->carrierInformation);
        $this->assertNull($msg->pricingOption[0]->currency);
        $this->assertNull($msg->pricingOption[0]->dateInformation);
        $this->assertNull($msg->pricingOption[0]->formOfPaymentInformation);
        $this->assertNull($msg->pricingOption[0]->frequentFlyerInformation);
        $this->assertNull($msg->pricingOption[0]->locationInformation);
        $this->assertNull($msg->pricingOption[0]->ticketInformation);
    }
}
