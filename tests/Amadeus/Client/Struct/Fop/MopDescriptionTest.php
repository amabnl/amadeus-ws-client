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

use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;
use Amadeus\Client\RequestOptions\Fop\CreditCardSupplementaryData;
use Amadeus\Client\RequestOptions\Fop\DataOrSwitch;
use Amadeus\Client\RequestOptions\Fop\MopInfo;
use Amadeus\Client\RequestOptions\Fop\ThreeDSecureInfo;
use Amadeus\Client\Struct\Fop\AttributeDetails;
use Amadeus\Client\Struct\Fop\MopDescription;
use Test\Amadeus\BaseTestCase;

/**
 * MopDescriptionTest
 *
 * @package Test\Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MopDescriptionTest extends BaseTestCase
{
    public function testCanConstructWithSwitches()
    {
        $mopInfo = new MopInfo([
            'sequenceNr' => 1,
            'fopCode' => 'VI',
            'fopType' => MopInfo::FOPTYPE_FP_ELEMENT,
            'supplementarySwitches' => [
                new DataOrSwitch([
                    'type' => '13',
                    'description' => '1'
                ]),
                new DataOrSwitch([
                    'type' => '24',
                    'description' => '1'
                ]),
                new DataOrSwitch([
                    'type' => 'APM',
                    'description' => '1'
                ])
            ]
        ]);

        $obj = new MopDescription($mopInfo);


        $this->assertCount(1, $obj->mopDetails->pnrSupplementaryData);
        $this->assertEquals('S', $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaSetType);
        $this->assertCount(3, $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails);
        $this->assertEquals('13', $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[0]->attributeType);
        $this->assertEquals('1', $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[0]->attributeDescription);
        $this->assertEquals('24', $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[1]->attributeType);
        $this->assertEquals('1', $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[1]->attributeDescription);
        $this->assertEquals('APM', $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[2]->attributeType);
        $this->assertEquals('1', $obj->mopDetails->pnrSupplementaryData[0]->dataAndSwitchMap->criteriaDetails[2]->attributeDescription);
    }

    public function testCanConstructWithThreeDSecureVersionTwo()
    {
        $mopInfo = new MopInfo([
            'sequenceNr' => 1,
            'fopCode' => 'CCVI',
            'fopType' => 'CC',
            'fopStatus' => MopInfo::STATUS_NEW,
            'attributeType' => AttributeDetails::TYPE_FP_ELEMENT,
            'payMerchant' => 'EW',
            'mopPaymentType' => MopInfo::MOP_PAY_TYPE_CREDIT_CARD,
            'creditCardInfo' => new CreditCardInfo([
                'name' => 'Name Surname',
                'cardNumber' => 'XXXXXXXXXXXX0003',
                'vendorCode' => 'VI',
                'expiryDate' => '1020',
                'securityId' => '999',
                'supplementaryData' => [
                    new CreditCardSupplementaryData([
                        'setType' => CreditCardSupplementaryData::SET_TYPE_3DS,
                        'attributeType' => CreditCardSupplementaryData::ATTRIBUTE_TYPE_EXTERNAL_AUTHENTICATION,
                        'attributeDescription' => CreditCardSupplementaryData::ATTRIBUTE_DESCRIPTION_Y,
                    ])
                ],
                'threeDSecure' => new ThreeDSecureInfo([
                    'transactionsStatus' => ThreeDSecureInfo::PARES_AUTHENTICATION_SUCCESSFUL
                ])
            ])
        ]);

        $obj = new MopDescription($mopInfo);

        $this->assertSame(AttributeDetails::TYPE_FP_ELEMENT, $obj->paymentModule->groupUsage->attributeDetails[0]->attributeType);
        $this->assertSame(CreditCardSupplementaryData::SET_TYPE_3DS, $obj->paymentModule->mopDetailedData->creditCardDetailedData->cardSupplementaryData[0]->criteriaSetType);
    }
}
