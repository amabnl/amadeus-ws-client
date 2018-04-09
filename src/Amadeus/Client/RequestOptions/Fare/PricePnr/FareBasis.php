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

namespace Amadeus\Client\RequestOptions\Fare\PricePnr;

use Amadeus\Client\LoadParamsFromArray;

/**
 * FareBasis - Pricing options when pricing by fare basis (pricing type 'FBA')
 *
 * @package Amadeus\Client\RequestOptions\Fare
 * @author dieter <dermikagh@gmail.com>
 */
class FareBasis extends LoadParamsFromArray
{
    /**
     * @deprecated use PaxSegRef::TYPE_SEGMENT
     */
    const SEGREFTYPE_SEGMENT = 'S';
    /**
     * @deprecated use PaxSegRef::TYPE_PASSENGER
     */
    const SEGREFTYPE_PASSENGER = 'P';
    /**
     * @deprecated use PaxSegRef::TYPE_CONNECTING
     */
    const SEGREFTYPE_CONNECTING = 'X';

    const OVERRIDE_SIMPLE = "FBA";
    const OVERRIDE_FORCE = "FBL";

    /**
     * Type of Fare Basis override
     *
     * by default uses Simple override (FBA),
     * can be used in Forced mode too (FBL)
     *
     * @var string
     */
    public $overrideType = self::OVERRIDE_SIMPLE;

    /**
     * The first three letters of a fare basis
     *
     * @deprecated put the full fare basis in $this->fareBasisCode
     * @var string
     */
    public $fareBasisPrimaryCode;

    /**
     * The full Fare Basis code
     *
     * In legacy mode, this property holds the rest of the fare basis after $this->fareBasisPrimaryCode
     *
     * @var string
     */
    public $fareBasisCode;

    /**
     * The key is the segment/passenger tattoo number, the value is the segment type (self::SEGREFTYPE_*)
     *
     * @deprecated use $this->references instead
     * @var array
     */
    public $segmentReference = [];

    /**
     * Passenger & Segment references
     *
     * @var PaxSegRef[]
     */
    public $references = [];
}
