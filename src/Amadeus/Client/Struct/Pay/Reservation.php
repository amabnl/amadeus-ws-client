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

namespace Amadeus\Client\Struct\Pay;

use Amadeus\Client\RequestOptions\PayListVirtualCardsOptions;

/**
 * Reservation
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class Reservation
{
    /**
     * @var string
     */
    public $ID;

    /**
     * @var string
     */
    public $ExternalID;

    /**
     * Reservation constructor.
     *
     * @param PayListVirtualCardsOptions $params
     */
    public function __construct(PayListVirtualCardsOptions $params)
    {
        if ($params->Reservation->id !== null) {
            $this->ID = $params->Reservation->id;
        }

        if ($params->Reservation->externalId !== null) {
            $this->ExternalID = $params->Reservation->externalId;
        }
    }
}
