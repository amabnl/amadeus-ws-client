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

use Amadeus\Client\RequestOptions\PnrSplitOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Pnr\Split\SplitDetails;

/**
 * Structure class for representing the PNR_Split request message
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Mike Hernas <m@hern.as>
 */
class Split extends BaseWsMessage
{
    /**
     * @var ReservationInfo
     */
    public $reservationInfo;

    /**
     * @var SplitDetails
     */
    public $splitDetails;
    
    /**
     * Split constructor.
     *
     * @param PnrSplitOptions $params
     */
    public function __construct(PnrSplitOptions $params)
    {
        if (is_string($params->recordLocator) && strlen($params->recordLocator) >= 6) {
            $this->reservationInfo = new ReservationInfo($params->recordLocator);
        }
        if ($params->passengerTattoos) {
            $this->splitDetails = new SplitDetails($params->passengerTattoos);
        }
    }
}
