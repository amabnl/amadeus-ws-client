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

use Test\Amadeus\BaseTestCase;

use Amadeus\Client\RequestOptions\FopValidateFopOptions;
use Amadeus\Client\RequestOptions\Fop\Group;
use Amadeus\Client\RequestOptions\Fop\FopRef;

use Amadeus\Client\Struct\Fop\ValidateFormOfPayment;

/**
 * ValidateFOPTest
 *
 * @package Test\Amadeus\Client\Struct\Fop
 * @author Mike Hernas <mike@ahoy.io>
 */
class ValidateFOPTest extends BaseTestCase
{

    /**
     * 5.2 Operation: Validation of one FP element
     *
     * Query with one fopGroup
     *
     */
    public function testCanMakeMessageWithStructuredFreeFlow()
    {

        $opt = new FopValidateFopOptions([
            'fopGroup' => [new Group([
                'fopRef' => new FopRef([
                    'qualifier' => FopRef::QUAL_FORM_OF_PAYMENT_TATTOO,
                    'number' => 1
                ])
            ])]
        ]);
        $msg = new ValidateFormOfPayment($opt);

        $this->assertCount(1, $msg->fopGroup);
        $this->assertEquals('FPT', $msg->fopGroup[0]->fopReference->reference->qualifier);
        $this->assertEquals(1, $msg->fopGroup[0]->fopReference->reference->number);

    }

}
