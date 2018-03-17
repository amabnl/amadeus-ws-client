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

namespace Test\Amadeus\Client\Struct\Fare\MasterPricer;

use Amadeus\Client\RequestOptions\Fare\MasterPricer\FeeDetails;
use Amadeus\Client\RequestOptions\Fare\MasterPricer\MonetaryDetails;
use Amadeus\Client\RequestOptions\Fare\MPFeeOption;
use Amadeus\Client\Struct\Fare\MasterPricer\FeeOption;
use Test\Amadeus\BaseTestCase;

/**
 * FeeOptionTest
 *
 * @package Test\Amadeus\Client\Struct\Fare\MasterPricer
 * @author  Friedemann Schmuhl <friedemann@schmuhl.eu>
 */
class FeeOptionTest extends BaseTestCase
{
    public function testCanCreate()
    {
        $feeOptionRequest = new MPFeeOption([
                'type'       => MPFeeOption::TYPE_TICKETING_FEES,
                'feeDetails' => [
                    new FeeDetails([
                        'subType'         => FeeDetails::SUB_TYPE_FARE_COMPONENT_AMOUNT,
                        'option'          => FeeDetails::OPTION_MANUALLY_INCLUDED,
                        'monetaryDetails' => [
                            new MonetaryDetails(
                                [
                                    'amount' => 20.00
                                ]
                            )
                        ]
                    ])
                ]
            ]
        );

        $obj = new FeeOption($feeOptionRequest);

        $this->assertEquals("OB", $obj->feeTypeInfo->carrierFeeDetails->type);
        $this->assertEquals("FCA", $obj->feeDetails[0]->feeInfo->dataTypeInformation->subType);
        $this->assertEquals("IN", $obj->feeDetails[0]->feeInfo->dataTypeInformation->option);

        $this->assertEquals('C', $obj->feeDetails[0]->associatedAmounts->monetaryDetails[0]->typeQualifier);
        $this->assertEquals(20.00, $obj->feeDetails[0]->associatedAmounts->monetaryDetails[0]->amount);
    }
}
