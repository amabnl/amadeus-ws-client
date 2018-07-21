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

namespace Amadeus\Client\Struct\Pnr\Retrieve;

/**
 * Structure class for the RetrievalFacts message part for PNR_Retrieve messages
 *
 * @package Amadeus\Client\Struct\Pnr\Retrieve
 */
class RetrievalFacts
{
    /**
     * @var Retrieve
     */
    public $retrieve;
    /**
     * @var ReservationOrProfileIdentifier
     */
    public $reservationOrProfileIdentifier;
    /**
     * @var mixed
     * @todo Expand this structure
     */
    public $personalFacts;
    /**
     * @var mixed
     * @todo Expand this structure
     */
    public $frequentFlyer;
    /**
     * @var mixed
     * @todo Expand this structure
     */
    public $accounting;

    /**
     * Construct retrievalFacts element
     *
     * @param string $retrievalType
     * @param string|null $recordLocator (OPTIONAL)
     */
    public function __construct($retrievalType, $recordLocator = null)
    {
        $this->retrieve = new Retrieve($retrievalType);

        if ($recordLocator !== null) {
            $this->reservationOrProfileIdentifier =
                new ReservationOrProfileIdentifier($recordLocator);
        }
    }
}
