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

/**
 * Remarks
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Remarks
{
    /**
     * Remark type "Confidential remark"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Remark Type, coded codesets (Ref: 069C 1A 98.1.3)]
     * @var string
     */
    const TYPE_CONFIDENTIAL = "RC";
    /**
     * Remark type "Invoice remark"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Remark Type, coded codesets (Ref: 069C 1A 98.1.3)]
     * @var string
     */
    const TYPE_INVOICE = "RI";
    /**
     * Remark type "Miscellaneous remark"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Remark Type, coded codesets (Ref: 069C 1A 98.1.3)]
     * @var string
     */
    const TYPE_MISCELLANEOUS = "RM";
    /**
     * Remark type "Quality control remark"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Remark Type, coded codesets (Ref: 069C 1A 98.1.3)]
     * @var string
     */
    const TYPE_QUALCONTROL = "RQ";
    /**
     * Remark type "Corporate remark"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Remark Type, coded codesets (Ref: 069C 1A 98.1.3)]
     * @var string
     */
    const TYPE_CORP = "RX";

    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $category;
    /**
     * @var string
     */
    public $freetext;
    /**
     * @var string
     */
    public $providerType;
    /**
     * @var string
     */
    public $currency;
    /**
     * @var int
     */
    public $amount;
    /**
     * Array of strings (max 5)
     * @var string[]
     */
    public $officeId = [];

    /**
     * @param string $text
     * @param string|null $type self::TYPE_*
     * @param string|null $cat
     */
    public function __construct($text, $type = self::TYPE_MISCELLANEOUS, $cat = null)
    {
        $this->freetext = $text;
        $this->type = ($type === null) ? self::TYPE_MISCELLANEOUS : $type;

        if ($cat !== null && strlen($cat) === 1) {
            $this->category = $cat;
        }
    }
}
