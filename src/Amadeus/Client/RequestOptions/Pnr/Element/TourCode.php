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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;

/**
 * Tour Code element (FT)
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 * @author Dieter Devlieghere <dieter.devlieghere@gmail.com>
 */
class TourCode extends Element
{
    const PAX_INFANT_WITHOUT_SEAT = "766";
    const PAX_INFANT_WITH_SEAT = "767";
    const PAX_CBBG_CABIN_BAGGAGE = "C";
    const PAX_EXST_EXTRA_SEAT = "E";
    const PAX_GROUP = "G";
    const PAX_INFANT_NOT_OCCUPYING_A_SEAT = "INF";
    const PAX_MONTH = "MTH";
    const PAX_PASSENGER = "PAX";
    const PAX_YEAR = "YRS";

    /***
     * self::PAX_*
     *
     * @var string
     */
    public $passengerType;

    /**
     * Free format tour code string
     *
     * @var string
     */
    public $freeText;
}
