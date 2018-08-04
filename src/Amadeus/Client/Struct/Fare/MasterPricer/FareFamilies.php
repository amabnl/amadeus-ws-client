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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

use Amadeus\Client\RequestOptions\Fare\MasterPricer\FFCriteria;
use Amadeus\Client\RequestOptions\Fare\MPFareFamily;

/**
 * FareFamilies
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareFamilies
{
    /**
     * @var FamilyInformation
     */
    public $familyInformation;

    /**
     * @var FamilyCriteria
     */
    public $familyCriteria;

    /**
     * @var FareFamilySegment[]
     */
    public $fareFamilySegment = [];

    /**
     * @var OtherPossibleCriteria[]
     */
    public $otherPossibleCriteria = [];

    /**
     * FareFamilies constructor.
     *
     * @param MPFareFamily $family
     */
    public function __construct(MPFareFamily $family)
    {
        $this->familyInformation = new FamilyInformation(
            $family->name,
            $family->ranking
        );

        if ($family->criteria instanceof FFCriteria) {
            $this->familyCriteria = new FamilyCriteria($family->criteria);
        }

        foreach ($family->otherCriteria as $otherCriteria) {
            $this->otherPossibleCriteria[] = new OtherPossibleCriteria($otherCriteria);
        }
    }
}
