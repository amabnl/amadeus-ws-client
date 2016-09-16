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

namespace Test\Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\Fare\PricePnrBcFareBasis;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\CriteriaDetails;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionGroup;
use Amadeus\Client\Struct\Fare\PricePnr13\PricingOptionKey;
use Amadeus\Client\Struct\Fare\PricePNRWithBookingClass13;
use Test\Amadeus\BaseTestCase;

/**
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class PricePNRWithBookingClass13Test extends BaseTestCase
{
    public function testCanDoPricePnrCallWithStuff()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_NEG],
            'validatingCarrier' => 'BA',
            'currencyOverride' => 'EUR',
            'pricingsFareBasis' => [
                new PricePnrBcFareBasis([
                    'fareBasisPrimaryCode' => 'QNC',
                    'fareBasisCode' => '469W2',
                    'segmentReference' => [2 => PricePnrBcFareBasis::SEGREFTYPE_SEGMENT]
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference([2 => PricePnrBcFareBasis::SEGREFTYPE_SEGMENT]);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $negofarePo));
    }

    public function testCanDoPricePnrCallWithNoOptions()
    {
        $opt = new FarePricePnrWithBookingClassOptions();

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(1, $message->pricingOptionGroup);
        $this->assertEquals('NOP', $message->pricingOptionGroup[0]->pricingOptionKey->pricingOptionKey);
    }


    /**
     * Testcase where we have the Fare Basis override in the override options and also pricingsFareBasis provided
     * we will test that we only have 1 pricingOptionGroup for the fare basis override.
     */
    public function testCanDoPricePnrCallWithFareOverrideDuplicate()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FARETYPE_NEG, FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS],
            'validatingCarrier' => 'BA',
            'currencyOverride' => 'EUR',
            'pricingsFareBasis' => [
                new PricePnrBcFareBasis([
                    'fareBasisPrimaryCode' => 'QNC',
                    'fareBasisCode' => '469W2',
                    'segmentReference' => [2 => PricePnrBcFareBasis::SEGREFTYPE_SEGMENT]
                ])
            ]
        ]);

        $message = new PricePNRWithBookingClass13($opt);

        $this->assertCount(4, $message->pricingOptionGroup);

        $validatingCarrierPo = new PricingOptionGroup(PricingOptionKey::OPTION_VALIDATING_CARRIER);
        $validatingCarrierPo->carrierInformation = new CarrierInformation('BA');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $validatingCarrierPo));

        $currencyOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_CURRENCY_OVERRIDE);
        $currencyOverridePo->currency = new Currency('EUR');

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $currencyOverridePo));

        $fareBasisOverridePo = new PricingOptionGroup(PricingOptionKey::OPTION_FARE_BASIS_SIMPLE_OVERRIDE);
        $fareBasisOverridePo->optionDetail = new OptionDetail();
        $fareBasisOverridePo->optionDetail->criteriaDetails[] = new CriteriaDetails('QNC469W2');
        $fareBasisOverridePo->paxSegTstReference = new PaxSegTstReference([2 => PricePnrBcFareBasis::SEGREFTYPE_SEGMENT]);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $fareBasisOverridePo));

        $negofarePo = new PricingOptionGroup(PricingOptionKey::OPTION_NEGOTIATED_FARES);

        $this->assertTrue($this->assertArrayContainsSameObject($message->pricingOptionGroup, $negofarePo));
    }
}
