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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Pnr\Traveller as TravellerOptions;
use Amadeus\Client\RequestOptions\Pnr\TravellerGroup as TravellerGroupOptions;

/**
 * TravellerInfo
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TravellerInfo
{
    /**
     * @var ElementManagementPassenger
     */
    public $elementManagementPassenger;
    /**
     * Up to 2 PassengerData elements
     *
     * @var PassengerData[]
     */
    public $passengerData = [];
    /**
     * @todo expand this structure
     * @var array
     */
    public $enhancedPassengerData = [];

    /**
     * TravellerInfo constructor.
     *
     * @param TravellerOptions|null $traveller
     * @param TravellerGroupOptions|null $travellerGroup
     */
    public function __construct($traveller = null, $travellerGroup = null)
    {
        if ($traveller instanceof TravellerOptions) {
            $this->loadTraveller($traveller);
        } elseif ($travellerGroup instanceof TravellerGroupOptions) {
            $this->loadTravellerGroup($travellerGroup);
        }
    }

    /**
     * @param TravellerGroupOptions $group
     */
    protected function loadTravellerGroup($group)
    {
        $this->elementManagementPassenger = new ElementManagementPassenger(
            ElementManagementPassenger::SEG_GROUPNAME
        );

        $this->passengerData[] = new PassengerData($group->name);

        $this->passengerData[0]->travellerInformation->traveller->quantity = $group->nrOfTravellers;
        $this->passengerData[0]->travellerInformation->traveller->qualifier = Traveller::QUAL_GROUP;
    }

    /**
     * @param TravellerOptions $traveller
     */
    protected function loadTraveller(TravellerOptions $traveller)
    {
        $this->elementManagementPassenger = new ElementManagementPassenger(
            ElementManagementPassenger::SEG_NAME
        );

        $this->passengerData[] = new PassengerData($traveller->lastName);

        if (!is_null($traveller->number)) {
            $this->elementManagementPassenger->reference = new Reference(
                Reference::QUAL_PASSENGER,
                $traveller->number
            );
        }

        if ($traveller->firstName !== null || $traveller->travellerType !== null) {
            $this->passengerData[0]->travellerInformation->passenger[] = new Passenger(
                $traveller->firstName,
                $traveller->travellerType
            );
        }

        if ($traveller->withInfant === true || $traveller->infant !== null) {
            $this->addInfant($traveller);
        }

        if ($traveller->dateOfBirth instanceof \DateTime) {
            $this->passengerData[0]->dateOfBirth = new DateOfBirth(
                $this->formatDateOfBirth($traveller->dateOfBirth)
            );
        }
    }

    /**
     * Add infant
     *
     * 3 scenario's:
     * - infant without additional information
     * - infant with only first name provided
     * - infant with first name, last name & date of birth provided.
     *
     * @param TravellerOptions $traveller
     */
    protected function addInfant($traveller)
    {
        $this->passengerData[0]->travellerInformation->traveller->quantity = 2;

        if ($traveller->withInfant && is_null($traveller->infant)) {
            $this->makePassengerIfNeeded();
            $this->passengerData[0]->travellerInformation->passenger[0]->infantIndicator = Passenger::INF_NOINFO;
        } elseif ($traveller->infant instanceof TravellerOptions) {
            if (empty($traveller->infant->lastName)) {
                $this->makePassengerIfNeeded();
                $this->passengerData[0]->travellerInformation->passenger[0]->infantIndicator = Passenger::INF_GIVEN;

                $tmpInfantPassenger = new Passenger(
                    $traveller->infant->firstName,
                    Passenger::PASST_INFANT
                );

                $this->passengerData[0]->travellerInformation->passenger[] = $tmpInfantPassenger;
            } else {
                $this->makePassengerIfNeeded();
                $this->passengerData[0]->travellerInformation->passenger[0]->infantIndicator = Passenger::INF_FULL;

                $tmpInfant = new PassengerData($traveller->infant->lastName);
                $tmpInfant->travellerInformation->passenger[] = new Passenger(
                    $traveller->infant->firstName,
                    Passenger::PASST_INFANT
                );

                if ($traveller->infant->dateOfBirth instanceof \DateTime) {
                    $tmpInfant->dateOfBirth = new DateOfBirth(
                        $this->formatDateOfBirth($traveller->infant->dateOfBirth)
                    );
                }

                $this->passengerData[] = $tmpInfant;
            }
        }
    }

    /**
     * If there is no passenger node at
     * $travellerInfo->passengerData[0]->travellerInformation->passenger[0]
     * create one
     */
    protected function makePassengerIfNeeded()
    {
        if (count($this->passengerData[0]->travellerInformation->passenger) < 1) {
            $this->passengerData[0]->travellerInformation->passenger[0] = new Passenger(null, null);
        }
    }

    protected function formatDateOfBirth(\DateTime $dateOfBirth)
    {
        $day = (int) $dateOfBirth->format('d');
        if ($day < 10) {
            $day = "0$day";
        }

        $monthAndYear = strtoupper($dateOfBirth->format('My'));

        return $day . $monthAndYear;
    }
}
