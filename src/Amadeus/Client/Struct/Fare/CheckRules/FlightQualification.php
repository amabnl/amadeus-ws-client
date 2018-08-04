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

namespace Amadeus\Client\Struct\Fare\CheckRules;

use Amadeus\Client\Struct\WsMessageUtility;

/**
 * FlightQualificationForRules
 *
 * @package Amadeus\Client\Struct\Fare\CheckRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FlightQualification extends WsMessageUtility
{
    /**
     * 7AP AP - Between Eastern Hemisphere (TC2) and Eastern Hemisphere (TC3) via Atlantic and Pacific
     * 7AT AT - Between Eastern Hemisphere (TC2 & 3) and Western Hemisphere (TC1) via Atlantic
     * 7CA CA - Domestic Canada
     * 7CT CT - Circle trip, origin and destination the same, may be within the same area or between areas,
     *          not applicable via Atlantic and Pacific
     * 7EH EH - Within Eastern Hemisphere (TC2 & 3), except for PO and TS
     * 7FE FE - Between Russia Federation (west of Urals), Ukraine and TC3 direct (not via Siberia)
     * 7PA PA - Between Eastern Hemisphere (TC2 & 3) and Western Hemisphere (TC1) via Pacific
     * 7PE PE - Africa - TC1 via TC3
     * 7PN PN - South America - S. W. Pacific via N. American and/or Pacific
     * 7PO PO - Between Eastern Hemisphere (TC2) and Eastern Hemisphere (TC3) via Polar
     * 7RW RW - Round the world, origin and destination the same, via Atlantic and Pacific
     * 7SA SA - South America - So. East Asia direct or via Central Africa, southern Africa, Indian Ocean Islands
     * 7SP SP - Between Central & So. America and So. West Pacific (Australia/New Zealand/selected Pacific Is.)
     *          via South Pole
     * 7TB TB - Trans border
     * 7TS TS - Between Eastern Hemisphere (TC2) and Eastern Hemisphere (TC3) via Siberia
     * 7US US - Within US
     * 7WH WH - Within Western Hemisphere (TC1)
     * WX Weather
     *
     * @var string
     */
    public $movementType;

    /**
     * @var FareCategories
     */
    public $fareCategories;

    /**
     * @var FareDetails
     */
    public $fareDetails;

    /**
     * @var AdditionalFareDetails
     */
    public $additionalFareDetails;

    /**
     * @var DiscountDetails[]
     */
    public $discountDetails = [];

    /**
     * FlightQualification constructor.
     *
     * @param string|null $discountQualifier
     */
    public function __construct($discountQualifier = null)
    {
        if (!is_null($discountQualifier)) {
            $this->discountDetails[] = new DiscountDetails($discountQualifier);
        }
    }
}
