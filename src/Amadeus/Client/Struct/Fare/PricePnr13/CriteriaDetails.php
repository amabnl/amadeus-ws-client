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

namespace Amadeus\Client\Struct\Fare\PricePnr13;

/**
 * CriteriaDetails
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CriteriaDetails
{
    /**
     * @var string
     */
    public $attributeType;

    /**
     * @var string
     */
    public $attributeDescription;

    /**
     * Passenger, Segment or TST references to partially price the PNR
     *
     * @var PaxSegRef[]
     */
    public $paxSegTstReference = [];

    /**
     * CriteriaDetails constructor.
     *
     * @param string $type
     */
    public function __construct($type, $description = null, $references = null)
    {
        $this->attributeType = $type;
        if (isset($description))
            $this->attributeDescription = $description;
        if (isset($references))
            $this->paxSegTstReference = $references;
    }
}
