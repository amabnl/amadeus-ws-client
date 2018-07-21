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

namespace Amadeus\Client\Struct\Pnr\TransferOwnership;

/**
 * OfficeIdentification
 *
 * @package Amadeus\Client\Struct\Pnr\TransferOwnership
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OfficeIdentification
{
    /**
     * @var OfficeIdentificator
     */
    public $officeIdentificator;

    /**
     * @var SpecificChanges[]
     */
    public $specificChanges = [];

    /**
     * OfficeIdentification constructor.
     *
     * @param string|null $inHouseIdentification1
     * @param string|null $inHouseIdentification2
     */
    public function __construct($inHouseIdentification1 = null, $inHouseIdentification2 = null)
    {
        $this->officeIdentificator = new OfficeIdentificator(
            $inHouseIdentification1,
            $inHouseIdentification2
        );
    }

    /**
     * Load Specific Changes
     *
     * @param bool $changeTicketing Change Ticketing office?
     * @param bool $changeQueueing Change Queueing office?
     * @param bool $changeOptQueueEl Change the office specified in the option queue element?
     */
    public function loadSpecificChanges($changeTicketing, $changeQueueing, $changeOptQueueEl)
    {
        if ($changeTicketing) {
            $this->specificChanges[] = new SpecificChanges(
                SpecificChanges::ACTION_TICKETING_OFFICE
            );
        }
        if ($changeQueueing) {
            $this->specificChanges[] = new SpecificChanges(
                SpecificChanges::ACTION_QUEUEING_OFFICE
            );
        }
        if ($changeOptQueueEl) {
            $this->specificChanges[] = new SpecificChanges(
                SpecificChanges::ACTION_OPT_QUEUE_ELEMENT
            );
        }
    }
}
