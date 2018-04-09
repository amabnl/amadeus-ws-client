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

namespace Amadeus\Client\Struct\PriceXplorer;

/**
 * Structure class for the SelectionDetailsGroup message part for PriceXplorer_* messages
 *
 * @package Amadeus\Client\Struct\PriceXplorer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SelectionDetailsGroup
{
    /**
     * @var SelectionDetailsInfo
     */
    public $selectionDetailsInfo;
    
    /**
     * @var NbOfUnitsInfo
     */
    public $nbOfUnitsInfo;
    
    /**
     * @var DateAndTimeInfo
     */
    public $dateAndTimeInfo;
    
    /**
     * @var QuantityInfo
     */
    public $quantityInfo;
    
    /**
     * @var array
     */
    public $attributeInfo = [];

    /**
     * SelectionDetailsGroup constructor.
     *
     * @param bool $cheapestNonStop
     * @param bool $cheapestOverall
     */
    public function __construct($cheapestNonStop, $cheapestOverall)
    {
        $this->selectionDetailsInfo = new SelectionDetailsInfo(
            SelectionDetails::OPT_PRICE_RESULT_DISTRIBUTION
        );

        $this->nbOfUnitsInfo = new NbOfUnitsInfo();

        if ($cheapestNonStop === true) {
            $this->nbOfUnitsInfo->quantityDetails[] = new NumberOfUnitDetailsType(
                null,
                NumberOfUnitDetailsType::QUAL_CHEAPEST_NONSTOP
            );
        }

        if ($cheapestOverall === true) {
            $this->nbOfUnitsInfo->quantityDetails[] = new NumberOfUnitDetailsType(
                null,
                NumberOfUnitDetailsType::QUAL_CHEAPEST_OVERALL
            );
        }
    }
}
