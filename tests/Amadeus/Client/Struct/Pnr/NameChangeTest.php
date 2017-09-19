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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\Pnr\NameChange\Infant;
use Amadeus\Client\RequestOptions\Pnr\NameChange\Passenger;
use Amadeus\Client\RequestOptions\PnrNameChangeOptions;
use Amadeus\Client\Struct\Pnr\NameChange\Reference;
use Amadeus\Client\Struct\Pnr\NameChange\TransactionCode;
use Test\Amadeus\BaseTestCase;

/**
 * NameChangeTest
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class NameChangeTest extends BaseTestCase
{
    /**
     * 5.1 Operation: Name change on retrieved PNR
     *
     * The example shows the message required to change the name of the passenger specified by the reference number with the following data:
     *
     * Passenger surname: SURNAME
     * Passenger given name / title: GIVENNAME MR
     * Passenger reference number: 1
     * Passenger type code: ADT
     * Infant name: SMITH
     * Infant given name: BABY
     * Infant date of birth: 15 SEP 2007
     *
     * Note: There must be a retrieved PNR in the AAA. No record locator information needed as the PNR must be already in context.
     */
    public function testCanCreateMessageOperation51()
    {
        $opt = new PnrNameChangeOptions([
            'operation' => PnrNameChangeOptions::OPERATION_CHANGE,
            'passengers' => [
                new Passenger([
                    'reference' => 1,
                    'type' => 'ADT',
                    'lastName' => 'SURNAME',
                    'firstName' => 'GIVENNAME MR',
                    'infant' => new Infant([
                        'lastName' => 'SMITH',
                        'firstName' => 'BABY',
                        'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '2007-09-15', new \DateTimeZone('UTC'))
                    ])
                ])
            ]
        ]);

        $msg = new NameChange($opt);

        $this->assertEquals(TransactionCode::CODE_CHANGE, $msg->transactionCode->actionRequestCode);
        $this->assertCount(1, $msg->enhancedPassengerGroup);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->value);
        $this->assertEquals(Reference::TYPE_PASSENGER_TATTOO, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->type);
        $this->assertCount(2, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation);
        $this->assertEquals(3, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertEquals('ADT', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('GIVENNAME MR', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('SURNAME', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData);

        $this->assertEquals('INF', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('BABY', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('SMITH', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertEquals('2007', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->dateOfBirthInEnhancedPaxData->dateTime->year);
        $this->assertEquals('9', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->dateOfBirthInEnhancedPaxData->dateTime->month);
        $this->assertEquals('15', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->dateOfBirthInEnhancedPaxData->dateTime->day);
    }

    /**
     * 5.2 Operation: Name change with Native name on retrieved PNR
     *
     * The example shows the message required to change the name of the passenger specified by the reference number with the following data:
     *
     * Passenger surname: 김
     * Passenger given name: 지훈
     * Passenger reference number: 1
     * Passenger type code: ADT
     * Infant name: SMITH
     * Infant given name: BABY
     * Infant date of birth: 15 SEP 2011
     *
     * Note: There must be a retrieved PNR in the context.
     */
    public function testCanCreateMessageOperation52()
    {
        $opt = new PnrNameChangeOptions([
            'operation' => PnrNameChangeOptions::OPERATION_CHANGE,
            'passengers' => [
                new Passenger([
                    'reference' => 1,
                    'type' => 'ADT',
                    'nativeLastName' => '&#44608;',
                    'nativeFirstName' => '&#51648;&#54984;',
                    'infant' => new Infant([
                        'lastName' => 'SMITH',
                        'firstName' => 'BABY',
                        'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '2011-09-15', new \DateTimeZone('UTC'))
                    ])
                ])
            ]
        ]);

        $msg = new NameChange($opt);


        $this->assertEquals(TransactionCode::CODE_CHANGE, $msg->transactionCode->actionRequestCode);
        $this->assertCount(1, $msg->enhancedPassengerGroup);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->value);
        $this->assertEquals(Reference::TYPE_PASSENGER_TATTOO, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->type);
        $this->assertCount(2, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation);
        $this->assertEquals(3, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertEquals('ADT', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('&#51648;&#54984;', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('&#44608;', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData);

        $this->assertEquals('INF', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('BABY', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('SMITH', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertEquals('2011', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->dateOfBirthInEnhancedPaxData->dateTime->year);
        $this->assertEquals('9', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->dateOfBirthInEnhancedPaxData->dateTime->month);
        $this->assertEquals('15', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->dateOfBirthInEnhancedPaxData->dateTime->day);
    }

    /**
     * 5.4 Operation: Name completion with Native name on retrieved PNR
     *
     * Assumption -The PNR is created with a name element containing following data (Universal name already stored in name element):
     *
     * Passenger surname - SURNAME
     * Passenger given name - GIVENNAME
     * Separated Passenger title - MR
     * Passenger reference number - 3
     * The example shows the message required to complete the Name Element with the following data (Native Name):
     *
     * Passenger surname - ゴトウ
     * Passenger given name - トモヒロ
     * Separated Passenger title - MR
     * Passenger reference number - 3
     * Note: There must be a retrieved PNR in the context. No record locator information needed as the PNR must be already in context.
     */
    public function testCanCreateMessageOperation54()
    {
        $opt = new PnrNameChangeOptions([
            'operation' => PnrNameChangeOptions::OPERATION_COMPLETE,
            'passengers' => [
                new Passenger([
                    'reference' => 3,
                    'type' => 'ADT',
                    'lastName' => 'SURNAME',
                    'firstName' => 'GIVENNAME',
                    'title' => 'MR',
                    'nativeLastName' => '&#12468;&#12488;&#12454;',
                    'nativeFirstName' => '&#12488;&#12514;&#12498;&#12525;'
                ])
            ]
        ]);

        $msg = new NameChange($opt);


        $this->assertEquals(TransactionCode::CODE_COMPLETE, $msg->transactionCode->actionRequestCode);
        $this->assertCount(1, $msg->enhancedPassengerGroup);
        $this->assertEquals(3, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->value);
        $this->assertEquals(Reference::TYPE_PASSENGER_TATTOO, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->type);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertEquals('ADT', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(3, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(2, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('GIVENNAME', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('SURNAME', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertEquals('MR', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData);
        $this->assertEquals('&#12488;&#12514;&#12498;&#12525;', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[1]->givenName);
        $this->assertEquals('&#12468;&#12488;&#12454;', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[1]->surname);
        $this->assertEquals('MR', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[1]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[1]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[1]->referenceName);
    }

    public function testCanCreateMessageInfantOnlyGiven()
    {
        $opt = new PnrNameChangeOptions([
            'operation' => PnrNameChangeOptions::OPERATION_CHANGE,
            'passengers' => [
                new Passenger([
                    'reference' => 1,
                    'type' => 'ADT',
                    'lastName' => 'SURNAME',
                    'firstName' => 'GIVENNAME MR',
                    'infant' => new Infant([
                        'firstName' => 'BABY'
                    ])
                ])
            ]
        ]);

        $msg = new NameChange($opt);

        $this->assertEquals(TransactionCode::CODE_CHANGE, $msg->transactionCode->actionRequestCode);
        $this->assertCount(1, $msg->enhancedPassengerGroup);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->value);
        $this->assertEquals(Reference::TYPE_PASSENGER_TATTOO, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->type);
        $this->assertCount(2, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation);
        $this->assertEquals(2, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertEquals('ADT', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('GIVENNAME MR', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('SURNAME', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData);

        $this->assertEquals('INF', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('BABY', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[1]->dateOfBirthInEnhancedPaxData);
    }

    public function testCanCreateMessageInfantNoInfo()
    {
        $opt = new PnrNameChangeOptions([
            'operation' => PnrNameChangeOptions::OPERATION_CHANGE,
            'passengers' => [
                new Passenger([
                    'reference' => 1,
                    'type' => 'ADT',
                    'lastName' => 'SURNAME',
                    'firstName' => 'GIVENNAME MR',
                    'infant' => new Infant()
                ])
            ]
        ]);

        $msg = new NameChange($opt);

        $this->assertEquals(TransactionCode::CODE_CHANGE, $msg->transactionCode->actionRequestCode);
        $this->assertCount(1, $msg->enhancedPassengerGroup);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->value);
        $this->assertEquals(Reference::TYPE_PASSENGER_TATTOO, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->type);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertEquals('ADT', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('GIVENNAME MR', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('SURNAME', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData);
    }

    public function testCanCreateMessageWithMainPaxBIrthDate()
    {
        $opt = new PnrNameChangeOptions([
            'operation' => PnrNameChangeOptions::OPERATION_CHANGE,
            'passengers' => [
                new Passenger([
                    'reference' => 1,
                    'type' => 'ADT',
                    'lastName' => 'SURNAME',
                    'firstName' => 'GIVENNAME MR',
                    'dateOfBirth' => \DateTime::createFromFormat('Y-m-d', '1948-02-20', new \DateTimeZone('UTC'))
                ])
            ]
        ]);

        $msg = new NameChange($opt);

        $this->assertEquals(TransactionCode::CODE_CHANGE, $msg->transactionCode->actionRequestCode);
        $this->assertCount(1, $msg->enhancedPassengerGroup);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->value);
        $this->assertEquals(Reference::TYPE_PASSENGER_TATTOO, $msg->enhancedPassengerGroup[0]->elementManagementPassenger->reference->type);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->infantIndicator);
        $this->assertEquals('ADT', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->type);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->quantity);
        $this->assertEquals(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->uniqueCustomerIdentifier);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->age);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->travellerIdentificationCode);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->travellerNameInfo->otherType);
        $this->assertCount(1, $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails);
        $this->assertEquals('GIVENNAME MR', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->givenName);
        $this->assertEquals('SURNAME', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->surname);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->title);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->nameType);
        $this->assertNull($msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->enhancedTravellerNameInfo->otherPaxNamesDetails[0]->referenceName);
        $this->assertEquals('1948', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData->dateTime->year);
        $this->assertEquals('02', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData->dateTime->month);
        $this->assertEquals('20', $msg->enhancedPassengerGroup[0]->enhancedPassengerInformation[0]->dateOfBirthInEnhancedPaxData->dateTime->day);
    }
}
