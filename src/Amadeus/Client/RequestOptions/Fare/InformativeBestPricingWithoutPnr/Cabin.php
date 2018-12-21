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

namespace Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr;

/**
 * Cabin
 *
 * new Cabin(
 *   Cabin::TYPE_FIRST_CABIN, Cabin::CLASS_BUSINESS
 * )
 *
 * @package Amadeus\Client\RequestOptions\Fare\InformativeBestPricingWithoutPnr
 * @author  tsari <tibor.sari@invia.de>
 */
class Cabin
{
    /**
     * Search only in the original cabin (the one from the segment)
     */
    const TYPE_DEFAULT = 'K';

    /**
     * Search only in the cabin(s) provided as "first cabin".
     */
    const TYPE_FIRST_CABIN = 'FC';

    /**
     * Search first in the cabin(s) provided as "first cabin", then in the cabin(s) provided as "second cabin".
     *
     * Note: must be used together with the "first cabin".
     */
    const TYPE_SECOND_CABIN = 'SC';

    /**
     * Search first in the cabin(s) provided as "first cabin", then in the cabin(s) provided as "second cabin",
     * and finally in the cabin(s) provided as "third cabin".
     *
     * Note: must be used together with the "first cabin" and "second cabin".
     */
    const TYPE_THIRD_CABIN = 'TC';

    /**
     * In case no fare is found in the cabin(s) provided with above option, defaults to any cabin.
     */
    const TYPE_DEFAULT_CABIN = 'P';

    const CLASS_FIRST            = 'F';
    const CLASS_BUSINESS         = 'C';
    const CLASS_ECONOMY          = 'Y';
    const CLASS_STANDARD_ECONOMY = 'M';
    const CLASS_PREMIUM_ECONOMY  = 'W';

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $class;

    /**
     * Cabin constructor.
     *
     * @param string $type
     * @param string $class
     */
    public function __construct($type, $class = null)
    {
        $this->type  = $type;
        $this->class = $class;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }
}
