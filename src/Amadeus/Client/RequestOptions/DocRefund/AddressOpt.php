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

namespace Amadeus\Client\RequestOptions\DocRefund;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Address Options
 *
 * @package Amadeus\Client\RequestOptions\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AddressOpt extends LoadParamsFromArray
{
    const TYPE_BILLING_ADDRESS = 'AB';

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type = self::TYPE_BILLING_ADDRESS;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $company;

    /**
     * @var string
     */
    public $addressLine1;

    /**
     * @var string
     */
    public $addressLine2;

    /**
     * @var string
     */
    public $city;

    /**
     * @var string
     */
    public $postalCode;

    /**
     * @var string
     */
    public $poBox;

    /**
     * @var string
     */
    public $state;

    /**
     * @var string
     */
    public $country;
}
