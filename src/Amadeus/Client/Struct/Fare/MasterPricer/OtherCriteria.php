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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * OtherCriteria
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OtherCriteria
{
    const NAME_CHANGEABILITY = "CHG";
    const NAME_DATE_RESTRICTIONS = "DAT";
    const NAME_EXPANDED_PARAMETERS = "EXP";
    const NAME_REFUNDABILITY = "REF";

    /**
     * self::NAME_*
     *
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $value;

    /**
     * OtherCriteria constructor.
     *
     * @param string $value
     * @param string $name self::NAME_*
     */
    public function __construct($value, $name)
    {
        $this->name = $name;
        $this->value = $value;
    }
}
