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

namespace Amadeus\Client\Struct\PriceXplorer;

/**
 * Structure class for the DateAndTimeDetails message part for PriceXplorer_* messages
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author  Dieter Devlieghere <dermikagh@gmail.com>
 */
class DateAndTimeDetails
{
    const QUAL_DATEOFDEP = "D";
    const QUAL_ENDDATE = "E";
    const QUAL_RETURNDATE = "R";
    const QUAL_STARTDATE = "S";
    
    /**
     * @var string self::QUAL_*
     */
    public $qualifier;
    
    /**
     * @var string Format: ddmmyy
     */
    public $date;
    
    /**
     * @var string
     */
    public $time;
    
    /**
     * @var string
     */
    public $otherQualifier;
    
    /**
     * @var string
     */
    public $otherTime;

    /**
     * @param string $qualifier self::QUAL_*
     * @param \DateTime $date
     */
    public function __construct($qualifier, $date)
    {
        $this->qualifier = $qualifier;
        $this->date = $date->format('dmy');
    }
}
