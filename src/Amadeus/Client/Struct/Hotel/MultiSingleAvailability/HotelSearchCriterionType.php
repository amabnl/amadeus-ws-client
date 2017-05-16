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

namespace Amadeus\Client\Struct\Hotel\MultiSingleAvailability;

/**
 * HotelSearchCriterionType
 *
 * @package Amadeus\Client\Struct\Hotel\MultiSingleAvailability
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class HotelSearchCriterionType
{
    /**
     * @var HotelAmenity[]
     */
    public $HotelAmenity = [];

    /**
     * @var RoomAmenity[]
     */
    public $RoomAmenity = [];

    /**
     * @var HotelFeature[]
     */
    public $HotelFeature = [];

    /**
     * @var Award[]
     */
    public $Award = [];

    /**
     * @var Recreation[]
     */
    public $Recreation = [];

    /**
     * @var Service[]
     */
    public $Service = [];

    /**
     * @var Transportation[]
     */
    public $Transportation = [];

    /**
     * @var StayDateRange
     */
    public $StayDateRange;

    /**
     * @var RateRange[]
     */
    public $RateRange = [];

    /**
     * @var RatePlanCandidates
     */
    public $RatePlanCandidates;

    /**
     * @var Profiles
     */
    public $Profiles;

    /**
     * @var RoomStayCandidates
     */
    public $RoomStayCandidates;

    /**
     * @var AcceptedPayments
     */
    public $AcceptedPayments;

    /**
     * @var Media[]
     */
    public $Media = [];

    /**
     * @var HotelMeetingFacility[]
     */
    public $HotelMeetingFacility = [];

    /**
     * @var MealPlan
     */
    public $MealPlan;

    /**
     * @var RebatePrograms
     */
    public $RebatePrograms;

    /**
     * @var UserGeneratedContent
     */
    public $UserGeneratedContent;

    /**
     * @var bool
     */
    public $ExactMatch;

    public $ImportanceType;

    public $Ranking;

    public $Position;

    public $Address;

    public $Telephone;

    public $RefPoint = [];

    public $CodeRef;

    /**
     * @var HotelRef[]
     */
    public $HotelRef = [];

    public $Radius;

    public $MapArea;

    public $AdditionalContents;
}
