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

use Amadeus\Client\RequestOptions\Pnr\Traveller;
use Amadeus\Client\RequestOptions\Pnr\TravellerGroup;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Traveller as PnrAddMultiTraveller;

/**
 * TravellerInfo
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
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
     * @param Traveller|null $traveller
     * @param TravellerGroup|null $travellerGroup
     */
    public function __construct($traveller = null, $travellerGroup = null)
    {
        if ($traveller instanceof Traveller) {
            $this->loadTraveller($traveller);
        } elseif ($travellerGroup instanceof TravellerGroup) {
            $this->loadTravellerGroup($travellerGroup);
        }
    }

    /**
     * @param TravellerGroup $group
     */
    protected function loadTravellerGroup($group)
    {
        $this->elementManagementPassenger = new ElementManagementPassenger(
            ElementManagementPassenger::SEG_GROUPNAME
        );

        $this->passengerData[] = new PassengerData($group->name);

        $this->passengerData[0]->travellerInformation->traveller->quantity = $group->nrOfTravellers;
        $this->passengerData[0]->travellerInformation->traveller->qualifier = PnrAddMultiTraveller::QUAL_GROUP;
    }

    /**
     * @param Traveller $traveller
     */
    protected function loadTraveller(Traveller $traveller)
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
                $traveller->dateOfBirth->format('dmY')
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
     * @param Traveller $traveller
     */
    protected function addInfant($traveller)
    {
        $this->passengerData[0]->travellerInformation->traveller->quantity = 2;

        if ($traveller->withInfant && is_null($traveller->infant)) {
            $this->makePassengerIfNeeded();
            $this->passengerData[0]->travellerInformation->passenger[0]->infantIndicator = Passenger::INF_NOINFO;
        } elseif ($traveller->infant instanceof Traveller) {
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
                        $traveller->infant->dateOfBirth->format('dmY')
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
}
