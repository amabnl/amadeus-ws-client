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

namespace Amadeus\Client\Struct\Hotel\Sell;

/**
 * StatusDetails
 *
 * @package Amadeus\Client\Struct\Hotel\Sell
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class StatusDetails
{
    const INDICATOR_GROUP_BILLING = "GB";
    const INDICATOR_GROUP_BOOKING = "GR";

    const ACTION_YES = 1;
    const ACTION_NO = 2;

    /**
     * self::INDICATOR_*
     *
     * @var string
     */
    public $indicator;

    /**
     * self::ACTION_*
     *
     * @var string|int
     */
    public $action;

    /**
     * StatusDetails constructor.
     *
     * @param string $indicator self::INDICATOR_*
     * @param int|string $action self::ACTION_*
     */
    public function __construct($indicator, $action)
    {
        $this->indicator = $indicator;
        $this->action = $action;
    }
}
