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

use Amadeus\Client\Struct\Offer\PassengerReference;

/**
 * GlobalBookingInfo
 *
 * @package Amadeus\Client\Struct\Offer\ConfirmHotel
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class GlobalBookingInfo
{
    public $markerGlobalBookingInfo;

    /**
     * @var ExternalBookingId
     */
    public $externalBookingId;

    /**
     * @var BookingSource
     */
    public $bookingSource;

    /**
     * @var BillableInfo
     */
    public $billableInfo;

    /**
     * @var TextOptions
     */
    public $textOptions;

    /**
     * @var SavingAmountInfo
     */
    public $savingAmountInfo;

    /**
     * @var RepresentativeParties[]
     */
    public $representativeParties = [];

    /**
     * @var KeyValueTree[]
     */
    public $keyValueTree = [];

    /**
     * GlobalBookingInfo constructor.
     *
     * @param int[]|null $passengers
     */
    public function __construct($passengers = null)
    {
        if (!is_null($passengers)) {
            foreach ($passengers as $singlePass) {
                $tmp = new RepresentativeParties();
                $tmp->occupantList = new OccupantList(
                    $singlePass,
                    PassengerReference::TYPE_PAXREF
                );
                $this->representativeParties[] = $tmp;
            }
        }
    }
}
