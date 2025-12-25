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

namespace Test\Amadeus\Client\Struct\Pnr\ListPassengersByFlight;

use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\SecondarySearchCriteria as SecondarySearchCriteriaOption;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\SsrOsiSk;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\PassengerNameOrType;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\Origin;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\FrequentFlyer as FrequentFlyerOption;
use Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight\NumericRange as NumericRangeOption;
use Amadeus\Client\Struct\Pnr\ListPassengersByFlight\SecondarySearchCriteria;
use Test\Amadeus\BaseTestCase;

/**
 * SecondarySearchCriteriaTest
 *
 * @package Test\Amadeus\Client\Struct\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class SecondarySearchCriteriaTest extends BaseTestCase
{
    public function testCanConstructWithSsrOsiSk()
    {
        $opt = new SecondarySearchCriteriaOption([
            'ssrOsiSk' => new SsrOsiSk([
                'ssrCode' => 'VGML',
                'serviceType' => 'SSR'
            ])
        ]);

        $struct = new SecondarySearchCriteria($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\SsrOsiSkValue', $struct->ssrOsiSkValue);
        $this->assertEquals('VGML', $struct->ssrOsiSkValue->specialRequirementsInfo->ssrCode);
        $this->assertEquals('SSR', $struct->ssrOsiSkValue->specialRequirementsInfo->serviceType);
    }

    public function testCanConstructWithCabinCode()
    {
        $opt = new SecondarySearchCriteriaOption([
            'cabinCode' => 'Y'
        ]);

        $struct = new SecondarySearchCriteria($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\CabinValue', $struct->cabinValue);
        $this->assertEquals('Y', $struct->cabinValue->cabinCode);
    }

    public function testCanConstructWithStatusCode()
    {
        $opt = new SecondarySearchCriteriaOption([
            'statusCode' => 'HK'
        ]);

        $struct = new SecondarySearchCriteria($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\StatusCodeValue', $struct->statusCodeValue);
        $this->assertEquals('HK', $struct->statusCodeValue->statusCode);
    }

    public function testCanConstructWithPassengerNameOrType()
    {
        $opt = new SecondarySearchCriteriaOption([
            'passengerNameOrType' => new PassengerNameOrType([
                'paxSurname' => 'SMITH',
                'paxType' => 'PAX',
                'otherPaxType' => 'CHD'
            ])
        ]);

        $struct = new SecondarySearchCriteria($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\PassengerNameOrTypeValue', $struct->passengerNameOrTypeValue);
        $this->assertEquals('SMITH', $struct->passengerNameOrTypeValue->paxDetails->surname);
        $this->assertEquals('PAX', $struct->passengerNameOrTypeValue->paxDetails->type);
        $this->assertEquals('CHD', $struct->passengerNameOrTypeValue->otherPaxDetails->type);
    }

    public function testCanConstructWithOrigin()
    {
        $opt = new SecondarySearchCriteriaOption([
            'origin' => new Origin([
                'sourceQualifier' => 'AS',
                'inHouseIdentification1' => 'MIA1S213F',
                'trueLocationId' => 'MIA',
                'countryCode' => 'US',
                'systemCode' => '1S'
            ])
        ]);

        $struct = new SecondarySearchCriteria($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\OriginValue', $struct->originValue);
        $this->assertEquals('AS', $struct->originValue->sourceType->sourceQualifier1);
        $this->assertEquals('MIA1S213F', $struct->originValue->originatorDetails->inHouseIdentification1);
        $this->assertEquals('US', $struct->originValue->countryCode);
        $this->assertEquals('MIA', $struct->originValue->locationDetails->trueLocationId);
        $this->assertEquals('1S', $struct->originValue->systemCode);
    }

    public function testCanConstructWithFrequentFlyer()
    {
        $opt = new SecondarySearchCriteriaOption([
            'frequentFlyer' => new FrequentFlyerOption([
                'airline' => 'LH',
                'tierLevel' => 'GOLD'
            ])
        ]);

        $struct = new SecondarySearchCriteria($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\FrequentFlyerValue', $struct->frequentFlyerValue);
    }

    public function testCanConstructWithNumericRange()
    {
        $opt = new SecondarySearchCriteriaOption([
            'numericRange' => new NumericRangeOption([
                'min' => 1,
                'max' => 10,
                'dataType' => 'NIP'
            ])
        ]);

        $struct = new SecondarySearchCriteria($opt);

        $this->assertInstanceOf('Amadeus\Client\Struct\Pnr\ListPassengersByFlight\NumericRangeValue', $struct->numericRangeValue);
    }
}
