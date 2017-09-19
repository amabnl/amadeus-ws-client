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

namespace Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass;

use Amadeus\Client\Struct\Fare\PricePnr13\CarrierInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\Currency;
use Amadeus\Client\Struct\Fare\PricePnr13\DateInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\FormOfPaymentInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\FrequentFlyerInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\LocationInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\MonetaryInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\OptionDetail;
use Amadeus\Client\Struct\Fare\PricePnr13\PaxSegTstReference;
use Amadeus\Client\Struct\Fare\PricePnr13\PenDisInformation;
use Amadeus\Client\Struct\Fare\PricePnr13\TaxInformation;

/**
 * PricingOption
 *
 * @package Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PricingOption
{
    /**
     * @var PricingOptionKey
     */
    public $pricingOptionKey;

    /**
     * @var OptionDetail
     */
    public $optionDetail;

    /**
     * @var CarrierInformation
     */
    public $carrierInformation;

    /**
     * @var Currency
     */
    public $currency;

    /**
     * @var PenDisInformation
     */
    public $penDisInformation;

    /**
     * @var MonetaryInformation
     */
    public $monetaryInformation;

    /**
     * @var TaxInformation[]
     */
    public $taxInformation = [];

    /**
     * @var DateInformation[]
     */
    public $dateInformation = [];

    /**
     * @var FrequentFlyerInformation
     */
    public $frequentFlyerInformation;

    /**
     * @var FormOfPaymentInformation
     */
    public $formOfPaymentInformation;

    /**
     * @var LocationInformation
     */
    public $locationInformation;

    /**
     * @var CouponInformation
     */
    public $couponInformation;

    /**
     * @var PaxSegTstReference
     */
    public $paxSegTstReference;

    /**
     * PricingOption constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->pricingOptionKey = new PricingOptionKey($key);
    }
}
