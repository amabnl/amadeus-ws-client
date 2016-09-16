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
use Amadeus\Client\Struct\Fare\PricePNRWithBookingClass12;
use Test\Amadeus\BaseTestCase;

/**
 * @package Amadeus\Client\Struct\Fare
 * @author dieter <dieter.devlieghere@benelux.amadeus.com>
 */
class PricePNRWithBookingClass12Test extends BaseTestCase
{
    public function testCanDoPricePnrCallWithStuff()
    {
        $opt = new FarePricePnrWithBookingClassOptions([
            'overrideOptions' => [FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS],
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

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_FAREBASIS, $message->overrideInformation->attributeDetails[0]->attributeType);
        $this->assertEquals('QNC', $message->pricingFareBase[0]->fareBasisOptions->fareBasisDetails->primaryCode);
        $this->assertEquals('469W2', $message->pricingFareBase[0]->fareBasisOptions->fareBasisDetails->fareBasisCode);
        $this->assertEquals(2, $message->pricingFareBase[0]->fareBasisSegReference[0]->refDetails->refNumber);
        $this->assertEquals(PricePnrBcFareBasis::SEGREFTYPE_SEGMENT, $message->pricingFareBase[0]->fareBasisSegReference[0]->refDetails->refQualifier);
        $this->assertEquals('BA', $message->validatingCarrier->carrierInformation->carrierCode);
        $this->assertEquals('EUR', $message->currencyOverride->firstRateDetail->currencyCode);
    }


    public function testCanDoPricePnrCallWithNoOptions()
    {
        $opt = new FarePricePnrWithBookingClassOptions();

        $message = new PricePNRWithBookingClass12($opt);

        $this->assertCount(1, $message->overrideInformation->attributeDetails);
        $this->assertEquals(FarePricePnrWithBookingClassOptions::OVERRIDE_NO_OPTION, $message->overrideInformation->attributeDetails[0]->attributeType);
    }
}
