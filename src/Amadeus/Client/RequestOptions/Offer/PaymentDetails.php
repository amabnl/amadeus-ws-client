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

namespace Amadeus\Client\RequestOptions\Offer;

use Amadeus\Client\LoadParamsFromArray;

/**
 * PaymentDetails
 *
 * @package Amadeus\Client\RequestOptions\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaymentDetails extends LoadParamsFromArray
{
    /**
     * 2-character company code for the credit card vendor
     *
     * VI = Visa
     * AX = American Express
     * BC = BC Card
     * CA = MasterCard
     * DS = Discover
     * DC = Diners Club
     * T = Carta Si
     * R = Carte Bleue
     * E = Visa Electron
     * JC = Japan Credit Bureau
     * TO = Maestro
     * ...
     *
     * @var string
     */
    public $ccVendor;

    /**
     * The credit card number
     *
     * @var string
     */
    public $ccCardNumber;

    /**
     * The name on the credit card
     *
     * @var string
     */
    public $ccCardHolder;

    /**
     * MMYY - expiry date of credit card
     *
     * @var string
     */
    public $ccExpiry;
}
