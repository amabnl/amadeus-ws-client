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

namespace Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\FopValidateFopOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * ValidateFormOfPayment
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Mike Hernas <mike@ahoy.io>
 */
class ValidateFormOfPayment extends BaseWsMessage
{
    /**
     * @var FopGroup[]
     */
    public $fopGroup = [];

    /**
     * FOP_ValidateFormOfPayment constructor.
     *
     * @param FopValidateFopOptions $options
     */
    public function __construct(FopValidateFopOptions $options)
    {
        foreach ($options->fopGroup as $group) {
            $this->fopGroup[] = new FopGroup($group);
        }
    }
}
