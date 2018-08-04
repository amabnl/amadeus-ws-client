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
 * TransactionDateTime
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TransactionDateTime
{
    const OPT_LOCAL_DATE_AND_TIME = "L";
    const OPT_LOCAL_TIME = "LT";
    const OPT_TRANSACTION_DATE_AND_TIME = "T";
    const OPT_UTC_TIME_MODE = "U";
    const OPT_GMT_TIME = "ZT";

    /**
     * self::OPT_*
     *
     * @var string
     */
    public $businessSemantic;

    /**
     * @var DateTime
     */
    public $dateTime;

    /**
     * TransactionDateTime constructor.
     *
     * @param string $businessSemantic self::OPT_*
     * @param \DateTime $dateTime
     */
    public function __construct($businessSemantic, \DateTime $dateTime)
    {
        $this->businessSemantic = $businessSemantic;
        $this->dateTime = new DateTime($dateTime);
    }
}
