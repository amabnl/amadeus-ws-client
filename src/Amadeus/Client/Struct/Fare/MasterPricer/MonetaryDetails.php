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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

use Amadeus\Client\RequestOptions\Fare\MasterPricer\MonetaryDetails as MonetaryDetailsRequest;

/**
 * MonetaryDetails
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Friedemann Schmuhl <friedemann@schmuhl.eu>
 */
class MonetaryDetails
{
    /**
     * Qualifier
     *
     * @var string
     */
    public $typeQualifier;

    /**
     * Amount
     *
     * @var string
     */
    public $amount;

    /**
     * Currency
     *
     * @var string
     */
    public $currency;

    /**
     * Location
     *
     * @var string
     */
    public $location;

    /**
     * MonetaryDetails constructor.
     *
     * @param MonetaryDetailsRequest $monetaryDetails
     */
    public function __construct(MonetaryDetailsRequest $monetaryDetails)
    {
        $this->typeQualifier = $monetaryDetails->typeQualifier;
        $this->amount        = $monetaryDetails->amount;
        $this->currency      = $monetaryDetails->currency;
        $this->location      = $monetaryDetails->location;
    }
}
