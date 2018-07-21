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

/**
 * FamilyCriteria
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FamilyCriteria
{
    const CABIN_MAJOR = "MC";
    const CABIN_RECOMMENDED = "RC";

    /**
     * @var string[]
     */
    public $carrierId = [];

    /**
     * @var string[]
     */
    public $rdb = [];

    /**
     * @var FareFamilyInfo
     */
    public $fareFamilyInfo;

    /**
     * @var FareProductDetail[]
     */
    public $fareProductDetail = [];

    /**
     * @var CorporateInfo[]
     */
    public $corporateInfo = [];

    /**
     * @var CabinProduct[]
     */
    public $cabinProduct = [];

    /**
     * self::CABIN_*
     *
     * @var string
     */
    public $cabinProcessingIdentifier;

    /**
     * @var DateTimeDetails[]
     */
    public $dateTimeDetails = [];

    /**
     * @var OtherCriteria[]
     */
    public $otherCriteria = [];

    /**
     * FamilyCriteria constructor.
     *
     * @param FFCriteria $criteria
     */
    public function __construct(FFCriteria $criteria)
    {
        $this->carrierId = $criteria->carriers;

        $familyInfoQual = $this->makeFamilyInfoQualifier($criteria);
        if (!is_null($familyInfoQual)) {
            $this->fareFamilyInfo = new FareFamilyInfo($familyInfoQual);
        }

        $this->loadFareProductDetail($criteria);

        $this->loadCorporateInfo($criteria);

        $this->loadCabinAndBookingClass($criteria);

        $this->loadOtherCriteria($criteria->expandedParameters);
    }

    /**
     * @param FFCriteria $criteria
     * @return string|null
     */
    protected function makeFamilyInfoQualifier($criteria)
    {
        $qual = null;
        if ($criteria->combinable === false) {
            $qual = FareFamilyInfo::QUAL_NON_COMBINABLE_FARE_FAMILY;
        } elseif ($criteria->alternatePrice === true) {
            $qual = FareFamilyInfo::QUAL_ALTERNATE_PRICE;
        }

        return $qual;
    }

    /**
     * @param FFCriteria $criteria
     */
    protected function loadCorporateInfo(FFCriteria $criteria)
    {
        foreach ($criteria->corporateCodes as $corporateCode) {
            $this->corporateInfo[] = new CorporateInfo($corporateCode);
        }

        foreach ($criteria->corporateNames as $corporateName) {
            $this->corporateInfo[] = new CorporateInfo(null, $corporateName);
        }
    }

    /**
     * @param FFCriteria $criteria
     */
    protected function loadFareProductDetail(FFCriteria $criteria)
    {
        foreach ($criteria->fareBasis as $fareBasis) {
            $this->fareProductDetail[] = new FareProductDetail($fareBasis);
        }
        foreach ($criteria->fareType as $fareType) {
            $this->fareProductDetail[] = new FareProductDetail(null, $fareType);
        }
    }

    /**
     * @param FFCriteria $criteria
     *
     */
    protected function loadCabinAndBookingClass($criteria)
    {
        foreach ($criteria->cabins as $cabin) {
            $this->cabinProduct[] = new CabinProduct($cabin);
        }

        $this->rdb = $criteria->bookingCode;
    }

    /**
     * @param string[] $expandedParameters
     */
    protected function loadOtherCriteria($expandedParameters)
    {
        foreach ($expandedParameters as $expandedParameter) {
            $this->otherCriteria[] = new OtherCriteria(
                $expandedParameter,
                OtherCriteria::NAME_EXPANDED_PARAMETERS
            );
        }
    }
}
