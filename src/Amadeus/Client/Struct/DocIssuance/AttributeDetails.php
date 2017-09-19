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

namespace Amadeus\Client\Struct\DocIssuance;

/**
 * AttributeDetails
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AttributeDetails
{
    const TYPE_REA_PRINTING = "ERP";
    const TYPE_ET_CONSOLIDATOR = "ETC";
    const TYPE_LOGICAL_PRINTER_ADDRESS = "LPA";
    const TYPE_OVERRIDE_CONTRACT_NATURE = "OCN";
    const TYPE_OPEN_SEGMENTS = "OPEN";
    const TYPE_PRICING = "PRE";
    const TYPE_SERVICE_PROVIDER = "SP";
    const TYPE_SATELLITE_TICKETING_PRINTING = "ST";
    const TYPE_REPORTING_TICKETING_INDICATOR = "STR";
    const TYPE_TIME_FORMAT = "TO";
    const TYPE_REVALIDATION = "TRV";

    /**
     * Give tag for a pair tag, value
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $attributeType;

    /**
     * Free form description of an attribute
     *
     * @var string
     */
    public $attributeDescription;

    /**
     * AttributeDetails constructor.
     *
     * @param string $attributeType self::TYPE_*
     * @param string $attributeDescription
     */
    public function __construct($attributeType, $attributeDescription)
    {
        $this->attributeType = $attributeType;
        $this->attributeDescription = $attributeDescription;
    }
}
