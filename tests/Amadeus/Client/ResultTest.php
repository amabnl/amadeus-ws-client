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

namespace Test\Amadeus\Client;

use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;
use Test\Amadeus\BaseTestCase;

/**
 * ResultTest
 *
 * @package Test\Amadeus\Client
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ResultTest extends BaseTestCase
{
    public function testCanConstruct()
    {
        $responseObject = new \stdClass();
        $responseObject->dummyProp = new \stdClass();

        $dummySendResult = new SendResult();
        $dummySendResult->responseObject = $responseObject;
        $dummySendResult->responseXml = 'dummy XML message';

        $result = new Result($dummySendResult);

        $this->assertEquals(Result::STATUS_OK, $result->status);
        $this->assertEquals('dummy XML message', $result->responseXml);
        $this->assertEquals($responseObject, $result->response);
    }
}
