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

namespace Amadeus\Client\RequestOptions\Fop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Ob Fee Computation Request options
 *
 * @package Amadeus\Client\RequestOptions\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ObFeeComputation extends LoadParamsFromArray
{
    const OPTION_OB_FEES = "OB";

    const OPTIONINF_EXEMPT_ALL_OB_FEES = "EX";

    /**
     * Departure Date
     * @var \DateTime
     */
    public $departureDate;

    /**
     * City code
     *
     * @var string
     */
    public $city;

    /***
     * self::OPTION_*
     *
     * @var string
     */
    public $option = self::OPTION_OB_FEES;

    /**
     * self::OPTIONINF_*
     *
     * @var string
     */
    public $optionInformation;
}
