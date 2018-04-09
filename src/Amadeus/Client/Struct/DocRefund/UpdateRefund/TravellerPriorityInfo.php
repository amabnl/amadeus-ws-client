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

namespace Amadeus\Client\Struct\DocRefund\UpdateRefund;

/**
 * TravellerPriorityInfo
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TravellerPriorityInfo
{
    const COMPANY_INDUSTRY_CAR_RENTAL = "7CC";
    const COMPANY_INDUSTRY_HOTEL_CHAINS = "7HH";

    /**
     * @var string
     */
    public $company;

    /**
     * @var string
     */
    public $dateOfJoining;

    /**
     * @var string
     */
    public $travellerReference;

    /**
     * TravellerPriorityInfo constructor.
     *
     * @param string|null $company
     * @param \DateTime|string|null $dateOfJoining
     * @param string|null $travellerReference
     */
    public function __construct($company = null, $dateOfJoining = null, $travellerReference = null)
    {
        $this->company = $company;
        $this->dateOfJoining = $this->loadDate($dateOfJoining);
        $this->travellerReference = $travellerReference;
    }

    /**
     * @param \DateTime|string|null $dateOfJoining
     * @return string|null
     */
    protected function loadDate($dateOfJoining)
    {
        $str = null;

        if ($dateOfJoining instanceof \DateTime) {
            $str = strtoupper($dateOfJoining->format('dMy'));
        } elseif (!empty($dateOfJoining)) {
            $str = $dateOfJoining;
        }

        return $str;
    }
}
