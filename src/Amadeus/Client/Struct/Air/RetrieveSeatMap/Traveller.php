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

namespace Amadeus\Client\Struct\Air\RetrieveSeatMap;

use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FrequentFlyer;
use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\Traveller as TravellerOpt;

/**
 * Traveller
 *
 * @package Amadeus\Client\Struct\Air\RetrieveSeatMap
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Traveller
{
    /**
     * @var TravelerInformation
     */
    public $travelerInformation;

    /**
     * @var FrequentTravelerDetails[]
     */
    public $frequentTravelerDetails = [];

    /**
     * @var FareInfo
     */
    public $fareInfo;

    /**
     * @var DateAndTimeInfo
     */
    public $dateAndTimeInfo;

    /**
     * @var TicketDetails
     */
    public $ticketDetails;

    /**
     * @var FareQualifierDetails
     */
    public $fareQualifierDetails;

    /**
     * @var CustomerCharacteristics[]
     */
    public $customerCharacteristics = [];

    /**
     * Traveller constructor.
     *
     * @param TravellerOpt $travellerOpt
     */
    public function __construct($travellerOpt)
    {
        $this->travelerInformation = new TravelerInformation($travellerOpt);

        $this->loadFrequentTraveller($travellerOpt->frequentTravellerInfo);

        $this->loadFareInfo($travellerOpt->passengerTypeCode, $travellerOpt->ticketDesignator);

        $this->loadBirthDate($travellerOpt->dateOfBirth);

        $this->loadTicketDetails($travellerOpt->ticketNumber);

        $this->loadFareQualifierDetails($travellerOpt->fareBasisOverride);
    }

    /**
     * @param FrequentFlyer|null $freqTrav
     */
    protected function loadFrequentTraveller($freqTrav)
    {
        if (!is_null($freqTrav)) {
            $this->frequentTravelerDetails[] = new FrequentTravelerDetails($freqTrav);
        }
    }

    /**
     * @param string|null $passTypeCode
     * @param string|null $ticketDesignator
     */
    protected function loadFareInfo($passTypeCode, $ticketDesignator)
    {
        if (!is_null($passTypeCode) || !is_null($ticketDesignator)) {
            $this->fareInfo = new FareInfo($passTypeCode, $ticketDesignator);
        }
    }

    /**
     * @param \DateTime|null $dateOfBirth
     */
    protected function loadBirthDate($dateOfBirth)
    {
        if ($dateOfBirth instanceof \DateTime) {
            $this->dateAndTimeInfo = new DateAndTimeInfo($dateOfBirth);
        }
    }

    /**
     * @param string|null $ticketNumber
     */
    protected function loadTicketDetails($ticketNumber)
    {
        if (!is_null($ticketNumber)) {
            $this->ticketDetails = new TicketDetails($ticketNumber);
        }
    }

    /**
     * @param string|null $fareBasisOverride
     */
    protected function loadFareQualifierDetails($fareBasisOverride)
    {
        if (!is_null($fareBasisOverride)) {
            $this->fareQualifierDetails = new FareQualifierDetails($fareBasisOverride);
        }
    }
}
