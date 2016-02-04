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

namespace Test\Amadeus\Client\ResponseHandler;

use Test\Amadeus\BaseTestCase;
use Amadeus\Client\ResponseHandler;

/**
 * BaseTest
 *
 * @package Test\Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class BaseTest extends BaseTestCase
{
    public function testCanThrowCorrectErrorForEmptyQueue()
    {
        $this->setExpectedException('\Amadeus\Client\Exception', "Queue category empty", 926);

        $xml = $this->getTestFile('emptyqueueresponse.txt');

        $respHandler = new ResponseHandler\Base();

        $respHandler->analyzeResponse($xml, 'Queue_List');
    }

    public function testWillThrowRuntimeExceptionWhenHandlingResponseFromUnknownMessage()
    {
        $this->setExpectedException('\RuntimeException', 'is not implemented');

        $respHandler = new ResponseHandler\Base();

        $respHandler->analyzeResponse('', 'Fare_DisplayFaresForCityPair');
    }

}
