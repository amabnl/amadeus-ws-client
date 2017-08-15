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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Structure class for representing the PNR_Retrieve request message
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Retrieve extends BaseWsMessage
{
    /**
     * Definition for retrieval type: by record locator
     *
     * See Amadeus Core Webservices documentation
     * [Retrieval type, coded codesets (Ref: 109P 1A 00.1.1)]
     *
     * @var int
     */
    const RETR_TYPE_BY_RECLOC = 2;
    const RETR_ACTIVE_PNR = 1;
    
    /**
     * @var Retrieve\Settings
     */
    public $settings;
    /**
     * @var Retrieve\RetrievalFacts
     */
    public $retrievalFacts;
    
    /**
     * Construct PNR_Retrieve message
     *
     * @param int $retrievalType
     * @param string|null $recordLocator (OPTIONAL)
     */
    public function __construct($retrievalType = self::RETR_TYPE_BY_RECLOC, $recordLocator = null)
    {
        $this->retrievalFacts = new Retrieve\RetrievalFacts(
            $retrievalType,
            $recordLocator
        );
    }
}
