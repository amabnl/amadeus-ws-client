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

namespace Amadeus\Client\Struct\Pnr\Cancel;

/**
 * Element
 *
 * @package Amadeus\Client\Struct\Pnr\Cancel
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Element
{
    const IDENT_SEGMENT_TATOO = "ST";

    const IDENT_PASSENGER_TATOO = "PT";

    const IDENT_OFFER_TATOO = "OOT";

    const IDENT_OTHER_TATOO = "OT";


    /**
     * self::IDENT_*
     *
     * Code giving specific meaning to a reference segment or a reference number.
     *
     * 001	Customer identification number
     * 002	Corporate identification number
     * D	Dominant segment in a marriage
     * ESG	ES element with receiver type G
     * ESI	ES element with receiver type I
     * ESP	ES element with receiver type P
     * N	Non dominant segment in a marriage
     * OOT	Offers - Other element tatoo reference number
     * OT	Other element tatoo reference number
     * PR	Passenger Client-request-message-defined ref. nbr
     * PT	Passenger tatoo reference number
     * SR	Segment Client-request-message-defined ref. nbr
     * SS	Segment Tatoo+SubTatoo reference number
     * ST	Segment Tatoo reference number
     *
     * @var string
     */
    public $identifier;

    /**
     * Identification number.
     *
     * @var string
     */
    public $number;

    /**
     * Identification number.
     *
     * @var int
     */
    public $subElement;


    /**
     * Element constructor.
     *
     * @param string $number
     * @param string $identifier self::IDENT_*
     * @param int|null $subElement
     */
    public function __construct($number, $identifier, $subElement = null)
    {
        $this->number = $number;
        $this->identifier = $identifier;
        $this->subElement = $subElement;
    }
}
