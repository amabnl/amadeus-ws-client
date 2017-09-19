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

namespace Amadeus\Client\Struct\Fop;

/**
 * ReferenceDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ReferenceDetails
{
    const TYPE_INSURANCE_ELEMENT_TATTOO = "INS";
    const TYPE_MISCELLEANOUS_DOCUMENT = "MCO";
    const TYPE_QUOTATION_RECORD_TATTOO = "QT";
    const TYPE_SPECIAL_SERVICE_REQUEST = "SEG";
    const TYPE_SSR_REFERENCE_NUMBER = "SSR";
    const TYPE_SERVICE_PRICING_RECORD_TATTOO = "TSM";
    const TYPE_TST = "TST";

    const TYPE_METHOD_OF_PAYMENT = "MEP";
    const TYPE_PAYMENT_ITEM = "PAI";
    const TYPE_SEQUENCE_NUMBER = "SEQ";

    const TYPE_CPF_BRAZILIAN_SECURITY_NUMBER = "CP";
    const TYPE_DRIVER_LICENSE = "DLN";
    const TYPE_FREQUENT_FLYER_NUMBER = "FFN";
    const TYPE_LOCALLY_DEFINED_NUMBER = "ID";
    const TYPE_NATIONAL_IDENTITY_CARD_NUMBER = "NI";
    const TYPE_PASSPORT_NUMBER = "PP";
    const TYPE_SOCIAL_SECURITY_NUMBER = "SSN";


    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var string|int
     */
    public $value;

    /**
     * ReferenceDetails constructor.
     *
     * @param string $type
     * @param int|string $value self::TYPE_*
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
