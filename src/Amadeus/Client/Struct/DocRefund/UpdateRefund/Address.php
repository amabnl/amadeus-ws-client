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

namespace Amadeus\Client\Struct\DocRefund\UpdateRefund;

/**
 * Address
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Address
{
    const OPTION_CITY = "CI";
    const OPTION_COUNTRY = "CO";
    const OPTION_COMPANY = "CY";
    const OPTION_ADDRESS_LINE_1 = "L1";
    const OPTION_ADDRESS_LINE_2 = "L2";
    const OPTION_NAME = "NA";
    const OPTION_PO_BOX = "PO";
    const OPTION_STATE = "ST";
    const OPTION_POSTAL_CODE = "ZP";

    /**
     * self::OPTION_*
     *
     * @var string
     */
    public $option;

    /**
     * @var string
     */
    public $optionText;

    /**
     * Address constructor.
     *
     * @param string $option self::OPTION_*
     * @param string $optionText
     */
    public function __construct($option, $optionText)
    {
        $this->option = $option;
        $this->optionText = $optionText;
    }
}
