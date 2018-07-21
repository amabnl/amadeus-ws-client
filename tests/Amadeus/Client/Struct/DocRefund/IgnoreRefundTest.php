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

namespace Test\Amadeus\Client\Struct\DocRefund;

use Amadeus\Client\RequestOptions\DocRefundIgnoreRefundOptions;
use Amadeus\Client\Struct\DocRefund\IgnoreRefund;
use Test\Amadeus\BaseTestCase;

/**
 * IgnoreRefundTest
 *
 * @package Test\Amadeus\Client\Struct\DocRefund
 * @author Mike Hernas <m@hern.as>
 */
class IgnoreRefundTest extends BaseTestCase
{
    /**
     * 5.1 Operation: An Example Use of Interface
     */
    public function testCanMakeMessageAtcRefundIgnore()
    {
        $opt = new DocRefundIgnoreRefundOptions([
          'actionRequest' => DocRefundIgnoreRefundOptions::ACTION_IGNORE
        ]);

        $msg = new IgnoreRefund($opt);

        $this->assertEquals(DocRefundIgnoreRefundOptions::ACTION_IGNORE, $msg->ignoreInformation->actionRequest);
    }
}
