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

namespace Amadeus\Client\Struct\Offer\ConfirmHotel;

/**
 * Reference
 *
 * @package Amadeus\Client\Struct\Offer\ConfirmHotel
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Reference
{
    const QUAL_OFFER_TATTOO = "OF";

    const QUAL_SEGMENT_TATTOO = "ST";

    /**
     * OF Offer element Tattoo
     * ST Segment Tattoo reference number
     *
     * @var string
     */
    public $qualifier;

    /**
     * @var string
     */
    public $number;

    /**
     * Reference constructor.
     *
     * @param string $number
     * @param string $qualifier
     */
    public function __construct($number, $qualifier = self::QUAL_OFFER_TATTOO)
    {
        $this->number = $number;
        $this->qualifier = $qualifier;
    }
}
