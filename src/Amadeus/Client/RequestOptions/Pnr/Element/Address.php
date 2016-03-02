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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;

/**
 * Address
 *
 * Billing address, Mailing address, structured or unstructured
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 */
class Address extends Element
{
    const TYPE_BILLING_UNSTRUCTURED = "ABU";

    const TYPE_MAILING_UNSTRUCTURED = "AMU";

    const TYPE_BILLING_STRUCTURED = "AB";

    const TYPE_MAILING_STRUCTURED = "AM";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * The entire address as unstructured text (use comma's for newlines)
     *
     * ONLY FOR UNSTRUCTURED ADDRESSES
     *
     * @var string
     */
    public $freeText;

    /**
     * Name - for structured address
     *
     * @var string
     */
    public $name;
    /**
     * Address line 1 - for structured address
     *
     * @var string
     */
    public $addressLine1;
    /**
     * Address line 2 - for structured address
     *
     * @var string
     */
    public $addressLine2;
    /**
     * City - for structured address
     *
     * @var string
     */
    public $city;
    /**
     * State - for structured address
     *
     * @var string
     */
    public $state;
    /**
     * Zip Code - for structured address
     *
     * @var string
     */
    public $zipCode;
    /**
     * Country - for structured address
     *
     * @var string
     */
    public $country;
    /**
     * Company - for structured address
     *
     * @var string
     */
    public $company;
}
