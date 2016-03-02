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

namespace Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Offer\ConfirmHotel\PnrInfo;
use Amadeus\Client\Struct\Offer\ConfirmHotel\RoomStayData;
use Amadeus\Client\Struct\Offer\ConfirmHotel\TattooReference;

/**
 * ConfirmHotel
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class ConfirmHotel extends BaseWsMessage
{
    /**
     * @var ConfirmHotel\PnrInfo
     */
    public $pnrInfo;

    /**
     * @var mixed
     */
    public $groupIndicator;

    /**
     * @var mixed
     */
    public $travelAgentRef;

    /**
     * @var ConfirmHotel\RoomStayData[]
     */
    public $roomStayData = [];

    /**
     * @var mixed
     */
    public $arrivalFlightDetailsGrp;

    /**
     * ConfirmHotel constructor.
     *
     * @param OfferConfirmHotelOptions $params
     */
    public function __construct(OfferConfirmHotelOptions $params)
    {
        if (!empty($params->recordLocator)) {
            $this->pnrInfo = new PnrInfo($params->recordLocator);
        }

        if (!empty($params->offerReference)) {
            $this->roomStayData[] = new RoomStayData();
            $this->roomStayData[0]->tattooReference = new TattooReference($params->offerReference);
        }
    }
}
