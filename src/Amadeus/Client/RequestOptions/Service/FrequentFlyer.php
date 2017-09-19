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

namespace Amadeus\Client\RequestOptions\Service;

use Amadeus\Client\LoadParamsFromArray;

/**
 * FrequentFlyer
 *
 * @package Amadeus\Client\RequestOptions\Service
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FrequentFlyer extends LoadParamsFromArray
{
    /**
     * 2-character airline code
     *
     * @var string
     */
    public $company;

    /**
     * A code to uniquely identify a frequent traveller.
     *
     * @var string
     */
    public $number;

    /**
     * Description of a membership level.
     *
     * @var string
     */
    public $tierLevel;

    /**
     * A unique number assigned by the sender to identify a level within a hierarchical structure.
     *
     * @var string
     */
    public $priorityCode;
}
