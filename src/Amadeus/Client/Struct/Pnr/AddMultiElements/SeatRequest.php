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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Pnr\Element\SeatRequest as SeatRequestOpt;

/**
 * SeatRequest
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SeatRequest
{
    /**
     * @var Seat
     */
    public $seat;

    /**
     * @var Special[]
     */
    public $special = [];

    /**
     * SeatRequest constructor.
     *
     * @param SeatRequestOpt $seatReq
     */
    public function __construct($seatReq)
    {
        if (!empty($seatReq->type)) {
            $this->seat = new Seat($seatReq->type);
        }

        if (!empty($seatReq->specialType)) {
            $this->special[] = new Special(null, $seatReq->specialType);
        }

        if (!empty($seatReq->seatNumber)) {
            if (is_array($seatReq->seatNumber)) {
                foreach ($seatReq->seatNumber as $seatNumber) {
                    $this->addSpecial($seatNumber);
                }
            } else {
                $this->addSpecial($seatReq->seatNumber);
            }
        }
    }

    protected function addSpecial($seatNumber)
    {
        $this->special[] = new Special($seatNumber);
    }
}
