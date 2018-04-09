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

use Amadeus\Client\RequestOptions\Pnr\Element\Address as AddressOptions;

/**
 * StructuredAddress
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StructuredAddress
{
    const TYPE_BILLING_ADDRESS = "2";

    const TYPE_MAILING_ADDRESS = "P08";

    const TYPE_MISC_ADDRESS = "P18";

    const TYPE_HOME_MAILING_ADDRESS = "P24";

    const TYPE_DELIVERY_MAILING_ADDRESS = "P25";

    /**
     * @var string
     */
    public $informationType;
    /**
     * @var Address
     */
    public $address;

    /**
     * @var OptionalData[]
     */
    public $optionalData = [];

    /**
     * StructuredAddress constructor.
     *
     * @param AddressOptions $params
     */
    public function __construct(AddressOptions $params)
    {
        $this->informationType = $this->makeType($params->type);

        $this->address = new Address($params->addressLine1);

        if (!empty($params->addressLine2)) {
            $this->optionalData[] = new OptionalData(
                $params->addressLine2,
                OptionalData::OPT_ADDRESS_LINE_2
            );
        };

        if (!empty($params->city)) {
            $this->optionalData[] = new OptionalData(
                $params->city,
                OptionalData::OPT_CITY
            );
        };

        if (!empty($params->country)) {
            $this->optionalData[] = new OptionalData(
                $params->country,
                OptionalData::OPT_COUNTRY
            );
        };

        if (!empty($params->name)) {
            $this->optionalData[] = new OptionalData(
                $params->name,
                OptionalData::OPT_NAME
            );
        };

        if (!empty($params->state)) {
            $this->optionalData[] = new OptionalData(
                $params->state,
                OptionalData::OPT_STATE
            );
        };

        if (!empty($params->zipCode)) {
            $this->optionalData[] = new OptionalData(
                $params->zipCode,
                OptionalData::OPT_ZIP_CODE
            );
        };

        if (!empty($params->company)) {
            $this->optionalData[] = new OptionalData(
                $params->company,
                OptionalData::OPT_COMPANY
            );
        };
    }

    /**
     * @param string $segmentType
     * @return string|null
     */
    protected function makeType($segmentType)
    {
        $infType = null;

        if ($segmentType === ElementManagementData::SEGNAME_ADDRESS_BILLING_STRUCTURED) {
            $infType = self::TYPE_BILLING_ADDRESS;
        } elseif ($segmentType === ElementManagementData::SEGNAME_ADDRESS_MAILING_STRUCTURED) {
            $infType = self::TYPE_MAILING_ADDRESS;
        }

        return $infType;
    }
}
