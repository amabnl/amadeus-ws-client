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

namespace Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Origin
 *
 * @package Amadeus\Client\RequestOptions\Pnr\ListPassengersByFlight
 * @author Evan Chuang <ycchuang1999@gmail.com>
 */
class Origin extends LoadParamsFromArray
{
    const SOURCE_QUALIFIER_OWNER = 2;

    const TRUE_LOCATION_ID_FRANKFURT = 'FRA';
    const TRUE_LOCATION_ID_NICE = 'NCE';

    const COUNTRY_CODE_AUSTRALIA = 'AU';
    const COUNTRY_CODE_GERMANY = 'DE';

    const SYSTEM_CODE_SABRE = '1S';

    /**
     * Source Qualifier
     *
     * self::SOURCE_QUALIFIER_*
     *
     * @var string|int
     */
    public $sourceQualifier = self::SOURCE_QUALIFIER_OWNER;

    /**
     * In-house Identification (Office ID or TTY address)
     *
     * @var string
     */
    public $inHouseIdentification1;

    /**
     * Place/location Identification
     *
     * self::TRUE_LOCATION_ID_*
     *
     * @var string
     */
    public $trueLocationId;

    /**
     * Country code of the system location
     *
     * self::COUNTRY_CODE_*
     *
     * @var string
     */
    public $countryCode;

    /**
     * Company identification
     *
     * self::SYSTEM_CODE_*
     *
     * @var string
     */
    public $systemCode;
}
