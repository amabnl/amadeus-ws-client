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

namespace Amadeus\Client\Struct\HeaderV2;

/**
 * Soap Header V2 Session header structure
 *
 * @package Amadeus\Client\Struct\HeaderV2
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Session
{
    /**
     * @var string
     */
    public $SessionId;
    /**
     * @var int
     */
    public $SequenceNumber;
    /**
     * @var string
     */
    public $SecurityToken;

    /**
     * @param string $sessId
     * @param int $seqNr
     * @param string $secTok
     */
    public function __construct($sessId, $seqNr, $secTok)
    {
        $this->SessionId = $sessId;
        $this->SequenceNumber = $seqNr;
        $this->SecurityToken = $secTok;
    }
}
