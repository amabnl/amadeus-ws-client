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

namespace Amadeus\Client\Struct\DocRefund\UpdateRefund;

use Amadeus\Client\RequestOptions\DocRefund\AddressOpt;

/**
 * StructuredAddress
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StructuredAddress
{
    const TYPE_BILLING_ADDRESS = "AB";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $informationType;

    /**
     * @var Address[]
     */
    public $address = [];

    /**
     * StructuredAddress constructor.
     *
     * @param AddressOpt $opt
     */
    public function __construct(AddressOpt $opt)
    {
        $this->informationType = $opt->type;

        $this->loadAddress($opt);
    }

    /**
     * @param AddressOpt $opt
     */
    protected function loadAddress($opt)
    {
        if (!empty($opt->company)) {
            $this->address[] = new Address(
                Address::OPTION_COMPANY,
                $opt->company
            );
        }
        if (!empty($opt->name)) {
            $this->address[] = new Address(
                Address::OPTION_NAME,
                $opt->name
            );
        }
        if (!empty($opt->addressLine1)) {
            $this->address[] = new Address(
                Address::OPTION_ADDRESS_LINE_1,
                $opt->addressLine1
            );
        }
        if (!empty($opt->addressLine2)) {
            $this->address[] = new Address(
                Address::OPTION_ADDRESS_LINE_2,
                $opt->addressLine2
            );
        }
        if (!empty($opt->city)) {
            $this->address[] = new Address(
                Address::OPTION_CITY,
                $opt->city
            );
        }
        if (!empty($opt->country)) {
            $this->address[] = new Address(
                Address::OPTION_COUNTRY,
                $opt->country
            );
        }
        if (!empty($opt->poBox)) {
            $this->address[] = new Address(
                Address::OPTION_PO_BOX,
                $opt->poBox
            );
        }
        if (!empty($opt->postalCode)) {
            $this->address[] = new Address(
                Address::OPTION_POSTAL_CODE,
                $opt->postalCode
            );
        }
        if (!empty($opt->state)) {
            $this->address[] = new Address(
                Address::OPTION_STATE,
                $opt->state
            );
        }
    }
}
