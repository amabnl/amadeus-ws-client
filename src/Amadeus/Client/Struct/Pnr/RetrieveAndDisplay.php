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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Pnr\RetrieveAndDisplay\DynamicOutputOption;
use Amadeus\Client\Struct\Pnr\RetrieveAndDisplay\StatusDetails;

/**
 * Structure class for representing the PNR_RetrieveAndDisplay request message
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RetrieveAndDisplay extends BaseWsMessage
{
    /**
     * @var ReservationInfo
     */
    public $reservationInfo;

    /**
     * @var RetrieveAndDisplay\PersonalFacts
     */
    public $personalFacts;

    /**
     * @var RetrieveAndDisplay\DynamicOutputOption
     */
    public $dynamicOutputOption;

    /**
     * @param string $recordLocator
     * @param string $option
     */
    public function __construct($recordLocator, $option = StatusDetails::OPTION_ALL)
    {
        $this->reservationInfo = new ReservationInfo($recordLocator);

        $this->dynamicOutputOption = new DynamicOutputOption($option);
    }
}
