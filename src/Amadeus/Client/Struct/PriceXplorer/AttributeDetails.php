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

namespace Amadeus\Client\Struct\PriceXplorer;

/**
 * Structure class for the AttributeDetails message part for PriceXplorer_* messages
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author  Dieter Devlieghere <dermikagh@gmail.com>
 */
class AttributeDetails
{
    const TYPE_DEPARTURE_DAY = "DAY";
    
    const TYPE_DESTINATION = "DES";
    
    const TYPE_MONTH = "MTH";
    
    const TYPE_PASSENGER_TYPE_PROFILE = "PRO";
    
    const TYPE_STAY_DURATION = "SD";
    
    const TYPE_WEEK = "WEEK";
    
    const TYPE_COUNTRY = "CTRY";
    
    /**
     * DAY     Per departure day
     * DES     Per destination
     * MTH     Per month
     * PRO     Passenger type profile
     * SD     Per stay duration
     * WEEK     Per week
     *
     * @var string self::TYPE_*
     */
    public $attributeType;
    /**
     * @var string
     */
    public $attributeDescription;
    
    /**
     * @param string $type self::TYPE_*
     */
    public function __construct($type)
    {
        $this->attributeType = $type;
    }
}
