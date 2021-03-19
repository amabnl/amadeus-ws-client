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

use Amadeus\Client\RequestOptions\Pnr\Retrieve\FrequentTraveller;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\Struct\Pnr\Retrieve as RetrieveMsg;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * Structure class for the RetrievalFacts message part for PNR_Retrieve messages
 *
 * @package Amadeus\Client\Struct\Pnr\Retrieve
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RetrievalFacts extends WsMessageUtility
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
     * @var PersonalFacts
     */
    public $personalFacts;

    /**
     * @var FrequentFlyer
     */
    public $frequentFlyer;

    /**
     * @var Accounting
     */
    public $accounting;

    /**
     * Construct retrievalFacts element
     *
     * @param PnrRetrieveOptions $options
     */
    public function __construct($options)
    {
        $this->retrieve = new Retrieve(
            $options->retrievalType,
            $options->officeId,
            $options->options
        );

        if ($this->checkAnyNotEmpty($options->accountNumber)) {
            $this->accounting = new Accounting($options->accountNumber);
        }

        if ($this->checkAnyNotEmpty($options->recordLocator, $options->customerProfile)) {
            $controlNumber = ($options->retrievalType === RetrieveMsg::RETR_TYPE_BY_CUSTOMER_PROFILE) ? $options->customerProfile : $options->recordLocator;

            $this->reservationOrProfileIdentifier = new ReservationOrProfileIdentifier($controlNumber);
        }

        if ($this->checkAnyNotEmpty($options->lastName, $options->departureDate, $options->ticket, $options->company, $options->flightNumber)) {
            $this->personalFacts = new PersonalFacts(
                $options->lastName,
                $options->departureDate,
                $options->ticket,
                $options->company,
                $options->flightNumber
            );
        }

        if ($options->frequentTraveller instanceof FrequentTraveller) {
            $this->frequentFlyer = new FrequentFlyer($options->frequentTraveller);
        }
    }
}
