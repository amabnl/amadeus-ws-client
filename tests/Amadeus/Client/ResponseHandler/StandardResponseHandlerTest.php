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

use Amadeus\Client\ResponseHandler\Ticket\HandlerCreateTSTFromPricing;
use Amadeus\Client\Result;
use Test\Amadeus\BaseTestCase;

/**
 * StandardResponseHandlerTest
 *
 * @package Test\Amadeus\Client\ResponseHandler
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StandardResponseHandlerTest extends BaseTestCase
{
    public function testCanGetUnknownStatusForUnknownErrorCode()
    {
        //Sweet sweet 100% coverage

        $respHandler = new HandlerCreateTSTFromPricing();

        $meth = $this->getMethod('Amadeus\Client\ResponseHandler\StandardResponseHandler', 'makeStatusFromErrorQualifier');

        $result = $meth->invoke($respHandler, 'ZZO');

        $this->assertEquals(Result::STATUS_UNKNOWN, $result);
    }

    public function testCanGetUnknownStatusForNull()
    {
        //Sweet sweet 100% coverage

        $respHandler = new HandlerCreateTSTFromPricing();

        $meth = $this->getMethod('Amadeus\Client\ResponseHandler\StandardResponseHandler', 'makeStatusFromErrorQualifier');

        $result = $meth->invoke($respHandler, null);

        $this->assertEquals(Result::STATUS_ERROR, $result);
    }
}
