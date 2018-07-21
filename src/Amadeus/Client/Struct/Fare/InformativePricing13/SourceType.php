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

namespace Amadeus\Client\Struct\Fare\InformativePricing13;

/**
 * SourceType
 *
 * @package Amadeus\Client\Struct\Fare\InformativePricing13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SourceType
{
    const SOURCETYPE_SAME_AS_ORIGINATOR = 3;
    const SOURCETYPE_OFFICE_SPECIFIED = 4;

    /**
     * @var int
     */
    public $sourceQualifier1;

    /**
     * @var int
     */
    public $sourceQualifier2;

    /**
     * @param int $sourceType
     */
    public function __construct($sourceType)
    {
        $this->sourceQualifier1 = $sourceType;
    }
}
