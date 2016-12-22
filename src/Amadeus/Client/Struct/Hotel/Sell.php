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

namespace Amadeus\Client\Struct\Hotel;

use Amadeus\Client\RequestOptions\HotelSellOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Hotel\Sell\ArrivalFlightDetailsGrp;
use Amadeus\Client\Struct\Hotel\Sell\BookingCompany;
use Amadeus\Client\Struct\Hotel\Sell\BookingPayerDetails;
use Amadeus\Client\Struct\Hotel\Sell\ExtraIndentification;
use Amadeus\Client\Struct\Hotel\Sell\GroupIndicator;
use Amadeus\Client\Struct\Hotel\Sell\RoomStayData;
use Amadeus\Client\Struct\Hotel\Sell\SystemIdentifier;
use Amadeus\Client\Struct\Hotel\Sell\TravelAgentRef;
use Amadeus\Client\Struct\Pnr\ReservationInfo;

/**
 * Hotel_Sell request structure
 *
 * @package Amadeus\Client\Struct\Hotel
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Sell extends BaseWsMessage
{
    /**
     * @var SystemIdentifier
     */
    public $systemIdentifier;

    /**
     * @var BookingCompany[]
     */
    public $bookingCompany = [];

    /**
     * @var ReservationInfo
     */
    public $reservationInfo;

    /**
     * @var ExtraIndentification
     */
    public $extraIndentification;

    /**
     * @var GroupIndicator
     */
    public $groupIndicator;

    /**
     * @var TravelAgentRef[]
     */
    public $travelAgentRef = [];

    /**
     * @var BookingPayerDetails
     */
    public $bookingPayerDetails;

    /**
     * @var RoomStayData[]
     */
    public $roomStayData = [];

    /**
     * @var ArrivalFlightDetailsGrp
     */
    public $arrivalFlightDetailsGrp;

    /**
     * Hotel_Sell constructor.
     *
     * @param HotelSellOptions $options
     */
    public function __construct(HotelSellOptions $options)
    {
        //TODO
    }
}
