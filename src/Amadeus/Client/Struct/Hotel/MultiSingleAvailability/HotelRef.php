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

namespace Amadeus\Client\Struct\Hotel\MultiSingleAvailability;

use Amadeus\Client\RequestOptions\Hotel\MultiSingleAvail\HotelReference;

/**
 * HotelRef
 *
 * @package Amadeus\Client\Struct\Hotel\MultiSingleAvailability
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class HotelRef
{
    /**
     * @var string
     */
    public $ChainCode;

    /**
     * @var string
     */
    public $BrandCode;

    /**
     * @var string
     */
    public $HotelCode;

    /**
     * @var string
     */
    public $HotelCityCode;

    /**
     * @var string
     */
    public $HotelName;

    /**
     * @var string
     */
    public $HotelCodeContext;

    /**
     * @var string
     */
    public $ChainName;

    /**
     * @var string
     */
    public $BrandName;

    /**
     * @var string
     */
    public $SegmentCategoryCode;

    /**
     * @var string
     */
    public $LocationCategoryCode;

    /**
     * @var string
     */
    public $ExtendedCitySearchIndicator;

    /**
     * HotelRef constructor.
     *
     * @param HotelReference $ref
     */
    public function __construct(HotelReference $ref)
    {
        $this->ChainCode = $ref->chainCode;
        $this->HotelCityCode = $ref->cityCode;
        $this->HotelCodeContext = $ref->codeContext;
        $this->HotelCode = $ref->hotelCode;
        $this->HotelName = $ref->name;
    }
}
