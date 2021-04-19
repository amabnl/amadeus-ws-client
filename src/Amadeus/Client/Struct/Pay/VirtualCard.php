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

use Amadeus\Client\RequestOptions\PayGenerateVirtualCardOptions;
use Amadeus\Client\Struct\InvalidArgumentException;

/**
 * VirtualCard
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class VirtualCard
{
    /**
     * @var string
     */
    public $CardName;

    /**
     * @var string
     */
    public $SubType;

    /**
     * @var string
     */
    public $CurrencyCode;

    /**
     * @var string
     */
    public $VendorCode;

    /**
     * @var boolean
     */
    public $ReturnCVV;

    /**
     * For DEBIT and PREPAID cards Amount to be loaded on the card
     * For CREDIT cards Credit limit
     * @var int
     */
    public $Amount;

    /**
     * @var int
     */
    public $DecimalPlaces;

    /**
     * @var Limitations
     */
    public $Limitations;

    /**
     * VirtualCard constructor.
     *
     * @param PayGenerateVirtualCardOptions $params
     */
    public function __construct(PayGenerateVirtualCardOptions $params)
    {
        if ($params->CardName !== null) {
            if (strlen($params->CardName) > 35) {
                throw new InvalidArgumentException('Max card name length 35');
            }

            $this->CardName = $params->CardName;
        }

        if ($params->Amount === null) {
            throw new InvalidArgumentException('You should set currency code');
        }

        if ($params->DecimalPlaces !== null) {
            $this->DecimalPlaces = $params->DecimalPlaces;
        }

        $this->Amount = $params->Amount;

        if ($params->CurrencyCode === null) {
            throw new InvalidArgumentException('You should set currency code');
        }

        $this->CurrencyCode = $params->CurrencyCode;

        if ($params->SubType !== null) {
            $this->SubType = $params->SubType;
        }

        if ($params->VendorCode !== null) {
            $this->VendorCode = $params->VendorCode;
        }


        if ($params->ReturnCVV !== null) {
            $this->ReturnCVV = $params->ReturnCVV;
        }


        $this->Limitations = new Limitations($params);
    }
}
