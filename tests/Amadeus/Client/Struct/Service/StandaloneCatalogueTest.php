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

use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Service\PaxSegRef;
use Amadeus\Client\RequestOptions\Service\StandaloneCatalogue\ServicePassenger;
use Amadeus\Client\RequestOptions\Service\StandaloneCatalogue\ServiceStandalonePricingOptions;
use Amadeus\Client\RequestOptions\ServiceStandaloneCatalogueOptions;
use Amadeus\Client\Struct\Service\StandaloneCatalogue;
use Test\Amadeus\BaseTestCase;

/**
 * StandaloneCatalogueTest
 *
 * @package Test\Amadeus\Client\Struct\Service
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class StandaloneCatalogueTest extends BaseTestCase
{
    public function testCanMakeMessage()
    {
        $opt = new ServiceStandaloneCatalogueOptions([
            'passengers' => [
                new ServicePassenger([
                    'reference' => 1,
                    'type' => ServicePassenger::TYPE_ADULT
                ])
            ],
            'segments' => [
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-13 10:10:00'),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-13 13:40:00'),
                    'from' => 'BOM',
                    'to' => 'DXB',
                    'marketingCompany' => 'EK',
                    'operatingCompany' => 'EK',
                    'flightNumber' => '505',
                    'bookingClass' => 'K',
                    'groupNumber' => 'V',
                    'segmentTattoo' => 1
                ]),
                new Segment([
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-27 21:55:00'),
                    'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', '2019-06-28 02:30:00'),
                    'from' => 'DXB',
                    'to' => 'BOM',
                    'marketingCompany' => 'EK',
                    'operatingCompany' => 'EK',
                    'flightNumber' => '500',
                    'bookingClass' => 'B',
                    'groupNumber' => 'V',
                    'segmentTattoo' => 2
                ])
            ],
            'pricingOptions' => new ServiceStandalonePricingOptions([
                'pricingsFareBasis' => [
                    new FareBasis([
                        'fareBasisCode' => 'KEXESIN1',
                    ])
                ],
                'references' => [
                    new PaxSegRef([
                        'reference' => 1,
                        'type' => 'S'
                    ]),
                    new PaxSegRef([
                        'reference' => 2,
                        'type' => 'S'
                    ]),
                    new PaxSegRef([
                        'reference' => 1,
                        'type' => 'P'
                    ])
                ]
            ])
        ]);

        $msg = new StandaloneCatalogue($opt);

        $this->assertEquals(1, $msg->passengerInfoGroup[0]->specificTravellerDetails->travellerDetails->referenceNumber);
        $this->assertEquals('ADT', $msg->passengerInfoGroup[0]->fareInfo->valueQualifier);

        $this->assertCount(2, $msg->flightInfo);
        $this->assertEquals(1, $msg->flightInfo[0]->flightDetails->itemNumber);
        $this->assertEquals('130619', $msg->flightInfo[0]->flightDetails->flightDate->departureDate);
        $this->assertEquals('1010', $msg->flightInfo[0]->flightDetails->flightDate->departureTime);
        $this->assertEquals('130619', $msg->flightInfo[0]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('1340', $msg->flightInfo[0]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals('BOM', $msg->flightInfo[0]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertEquals('DXB', $msg->flightInfo[0]->flightDetails->offpointDetails->trueLocationId);
        $this->assertEquals('EK', $msg->flightInfo[0]->flightDetails->companyDetails->marketingCompany);
        $this->assertEquals('EK', $msg->flightInfo[0]->flightDetails->companyDetails->operatingCompany);
        $this->assertEquals('505', $msg->flightInfo[0]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('K', $msg->flightInfo[0]->flightDetails->flightIdentification->bookingClass);
        $this->assertEquals('V', $msg->flightInfo[0]->flightDetails->flightTypeDetails->flightIndicator[0]);
        $this->assertEquals(2, $msg->flightInfo[1]->flightDetails->itemNumber);
        $this->assertEquals('270619', $msg->flightInfo[1]->flightDetails->flightDate->departureDate);
        $this->assertEquals('2155', $msg->flightInfo[1]->flightDetails->flightDate->departureTime);
        $this->assertEquals('280619', $msg->flightInfo[1]->flightDetails->flightDate->arrivalDate);
        $this->assertEquals('0230', $msg->flightInfo[1]->flightDetails->flightDate->arrivalTime);
        $this->assertEquals('DXB', $msg->flightInfo[1]->flightDetails->boardPointDetails->trueLocationId);
        $this->assertEquals('BOM', $msg->flightInfo[1]->flightDetails->offpointDetails->trueLocationId);
        $this->assertEquals('EK', $msg->flightInfo[1]->flightDetails->companyDetails->marketingCompany);
        $this->assertEquals('EK', $msg->flightInfo[1]->flightDetails->companyDetails->operatingCompany);
        $this->assertEquals('500', $msg->flightInfo[1]->flightDetails->flightIdentification->flightNumber);
        $this->assertEquals('B', $msg->flightInfo[1]->flightDetails->flightIdentification->bookingClass);
        $this->assertEquals('V', $msg->flightInfo[1]->flightDetails->flightTypeDetails->flightIndicator[0]);

        $this->assertEquals('FBA', $msg->pricingOption[0]->pricingOptionKey->pricingOptionKey);
        $this->assertEquals('KEXESIN1', $msg->pricingOption[0]->optionDetail->criteriaDetails[0]->attributeType);
        $this->assertEmpty($msg->pricingOption[0]->paxSegTstReference->referenceDetails);

        $this->assertEquals('SEL', $msg->pricingOption[1]->pricingOptionKey->pricingOptionKey);
        $this->assertNull($msg->pricingOption[1]->optionDetail);
        $this->assertCount(3, $msg->pricingOption[1]->paxSegTstReference->referenceDetails);
        $this->assertEquals('S', $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->type);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[0]->value);
        $this->assertEquals('S', $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->type);
        $this->assertEquals(2, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[1]->value);
        $this->assertEquals('P', $msg->pricingOption[1]->paxSegTstReference->referenceDetails[2]->type);
        $this->assertEquals(1, $msg->pricingOption[1]->paxSegTstReference->referenceDetails[2]->value);
    }
}
