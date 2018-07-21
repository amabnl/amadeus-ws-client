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

/**
 * ItemNumberDetails
 *
 * @package Amadeus\Client\Struct\Fare\CheckRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ItemNumberDetails
{
    const TYPE_FARE_COMPONENT = "FC";
    const TYPE_FREQUENT_TRAVELER_ACCOUNT_TO_BE_DECREMENTED = "700";
    const TYPE_TELETYPE_ADDRESS = "701";
    const TYPE_QUEUE_IDENTIFIER = "702";
    const TYPE_SUB_QUEUE_CATEGORY = "703";
    const TYPE_FIRST_BOOKED_SEGMENT = "704";
    const TYPE_LAST_BOOKED_SEGMENT = "705";
    const TYPE_ACCOUNT_NUMBER = "A";
    const TYPE_ALL_REQUEST_TYPE = "ART";
    const TYPE_CUSTOMER_NUMBER = "C";
    const TYPE_DOCUMENT_NUMBER = "D";
    const TYPE_PRODUCT_NUMBER = "P";
    const TYPE_TOTAL_OF_FARE_REQUIRED = "T";
    const TYPE_VERSION_REFERENCE_NUMBER = "V";

    /**
     * @var string
     */
    public $number;

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * ItemNumberDetails constructor.
     *
     * @param string $itemNumber
     * @param string|null $type self::TYPE_*
     */
    public function __construct($itemNumber, $type = null)
    {
        $this->number = $itemNumber;
        $this->type = $type;
    }
}
