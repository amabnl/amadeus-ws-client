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

namespace Amadeus\Client\Struct\Fop;

/**
 * AttributeDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AttributeDetails
{
    const TYPE_AUTHORISATION_ONLY = "AO";
    const TYPE_FC_ELEMENT = "FC";
    const TYPE_FP_ELEMENT = "FP";
    const TYPE_PAY_ELEMENT = "PAY";


    const TYPE_BEST_FARE_CANDIDATE = "BFAREC";
    const TYPE_CIND = "CIND";
    const TYPE_GWT_DATA = "GWTD";
    const TYPE_GWT_REQUIRED = "GWTR";
    const TYPE_CC_HOLDER_NAME = "HOLDN";
    const TYPE_ONO_DATA = "ONOD";
    const TYPE_ONO_REQUIRED = "ONOR";

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
     * @param string|null $attributeDescription
     */
    public function __construct($attributeType, $attributeDescription = null)
    {
        $this->attributeType = $attributeType;
        $this->attributeDescription = $attributeDescription;
    }
}
