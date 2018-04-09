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

namespace Amadeus\Client\Struct\Pnr\NameChange;

use Amadeus\Client\RequestOptions\Pnr\NameChange\Infant;
use Amadeus\Client\RequestOptions\Pnr\NameChange\Passenger;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * EnhancedPassengerGroup
 *
 * @package Amadeus\Client\Struct\Pnr\NameChange
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class EnhancedPassengerGroup extends WsMessageUtility
{
    /**
     * @var ElementManagementPassenger
     */
    public $elementManagementPassenger;

    /**
     * @var EnhancedPassengerInformation[]
     */
    public $enhancedPassengerInformation = [];

    /**
     * EnhancedPassengerGroup constructor.
     *
     * @param Passenger $passenger
     */
    public function __construct($passenger)
    {
        $this->elementManagementPassenger = new ElementManagementPassenger($passenger->reference);

        $infantIndicator = $this->makeInfantIndicator($passenger->infant);

        $this->enhancedPassengerInformation[] = $this->makeMainPax($passenger, $infantIndicator);

        if ($infantIndicator > 1) {
            $this->enhancedPassengerInformation[] = $this->makeInfant($passenger->reference, $passenger->infant);
        }
    }

    /**
     * @param Passenger|Infant $passenger
     * @return OtherPaxNamesDetails[]
     */
    protected function makeNamesForPax($passenger)
    {
        $details = [];

        if ($this->checkAnyNotEmpty($passenger->firstName, $passenger->lastName)) {
            $details[] = new OtherPaxNamesDetails(
                $passenger->lastName,
                $passenger->firstName,
                $passenger->title
            );
        }

        if ($this->checkAnyNotEmpty($passenger->nativeFirstName, $passenger->nativeLastName)) {
            $details[] = new OtherPaxNamesDetails(
                $passenger->nativeLastName,
                $passenger->nativeFirstName,
                $passenger->title
            );
        }

        return $details;
    }

    /**
     * @param Infant|null $infant
     * @return int|null
     */
    protected function makeInfantIndicator($infant)
    {
        $indicator = null;

        if ($infant instanceof Infant) {
            if ($this->hasLastAndFist($infant)) {
                $indicator = 3;
            } elseif ($this->hasFirst($infant)) {
                $indicator = 2;
            } else {
                $indicator = 1;
            }
        }

        return $indicator;
    }

    /**
     * @param Infant $infant
     * @return bool
     */
    protected function hasLastAndFist(Infant $infant)
    {
        return ($this->checkAllNotEmpty($infant->firstName, $infant->lastName) ||
            $this->checkAllNotEmpty($infant->nativeFirstName, $infant->nativeLastName));
    }

    /**
     * @param Infant $infant
     * @return bool
     */
    protected function hasFirst(Infant $infant)
    {
        return ($this->checkAllNotEmpty($infant->firstName) ||
            $this->checkAllNotEmpty($infant->nativeFirstName));
    }

    /**
     * @param Passenger $passenger
     * @param int|null $infantIndicator
     * @return EnhancedPassengerInformation
     */
    protected function makeMainPax($passenger, $infantIndicator)
    {
        $tmp = new EnhancedPassengerInformation();
        $tmp->enhancedTravellerNameInfo = new EnhancedTravellerNameInfo();
        $tmp->enhancedTravellerNameInfo->travellerNameInfo = new TravellerNameInfo(
            $passenger->reference,
            $passenger->type,
            $infantIndicator
        );

        $tmp->enhancedTravellerNameInfo->otherPaxNamesDetails = $this->makeNamesForPax($passenger);

        if ($passenger->dateOfBirth instanceof \DateTime) {
            $tmp->dateOfBirthInEnhancedPaxData = new DateOfBirthInEnhancedPaxData($passenger->dateOfBirth);
        }

        return $tmp;
    }

    /**
     * @param int $reference
     * @param Infant $infant
     * @return EnhancedPassengerInformation
     */
    protected function makeInfant($reference, $infant)
    {
        $tmp = new EnhancedPassengerInformation();
        $tmp->enhancedTravellerNameInfo = new EnhancedTravellerNameInfo();
        $tmp->enhancedTravellerNameInfo->travellerNameInfo = new TravellerNameInfo(
            $reference,
            'INF'
        );

        $tmp->enhancedTravellerNameInfo->otherPaxNamesDetails = $this->makeNamesForPax($infant);

        if ($infant->dateOfBirth instanceof \DateTime) {
            $tmp->dateOfBirthInEnhancedPaxData = new DateOfBirthInEnhancedPaxData($infant->dateOfBirth);
        }

        return $tmp;
    }
}
