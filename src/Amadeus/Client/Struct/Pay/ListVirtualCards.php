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

use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\RequestOptions\PayListVirtualCardsOptions;

/**
 * ListVirtualCards
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class ListVirtualCards extends BaseWsMessage
{
    public $Version = '2.0';

    /**
     * @var string
     */
    public $SubType;

    /**
     * @var string
     */
    public $VendorCode;

    /**
     * @var AmountRange
     */
    public $AmountRange;

    /**
     * @var string
     */
    public $CurrencyCode;

    /**
     * @var Period
     */
    public $Period;

    /**
     * @var string
     */
    public $CardStatus;

    /**
     * @var Reservation
     */
    public $Reservation;

    /**
     * ListVirtualCards constructor.
     * @param PayListVirtualCardsOptions $params
     * @param string|int                 $version
     */
    public function __construct(PayListVirtualCardsOptions $params, $version)
    {
        if ($params->SubType !== null) {
            $this->SubType = $params->SubType;
        }

        if ($params->VendorCode !== null) {
            $this->VendorCode = $params->VendorCode;
        }

        if ($params->AmountRange !== null) {
            $this->AmountRange = new AmountRange($params);
        }

        if ($params->CurrencyCode !== null) {
            $this->CurrencyCode = $params->CurrencyCode;
        }

        if ($params->Period !== null) {
            $this->Period = new Period($params);
        }

        if ($params->CardStatus !== null) {
            $this->CardStatus = $params->CardStatus;
        }

        if ($params->Reservation !== null) {
            $this->Reservation = new Reservation($params);
        }
    }
}
