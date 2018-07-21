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

namespace Amadeus\Client\Struct\Offer\ConfirmHotel;

/**
 * KeyValueTree
 *
 * @package Amadeus\Client\Struct\Offer\ConfirmHotel
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class KeyValueTree
{
    /**
     * 1 Member
     * 10 Financial statement
     * 11 Payment manner
     * 12 Loan information
     * 13 Contract
     * 14 Funding
     * 15 Acquisition phase
     * 16 Monetary appropriation
     * 17 Laboratory investigation
     * 18 Clinical investigation
     * 19 Reason for request
     * 2 Person
     * 20 Reason for prescription
     * 21 Comment to prescription
     * 22 Observation
     * 23 Comment to a request
     * 24 Event
     * 3 Array structure component
     * 4 University degree
     * 5 Professional title
     * 6 Courtesy title
     * 7 Directory set definition
     * 8 Structure object attribute
     * 9 Account
     * ZZZ Mutually defined
     *
     * @var string
     */
    public $attributeFunction;

    /**
     * @var AttributeDetails[]
     */
    public $attributeDetails = [];
}
