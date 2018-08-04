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

namespace Amadeus\Client\Struct\PointOfRef\Search;

/**
 * PorFndQryParams
 *
 * @package Amadeus\Client\Struct\PointOfRef\Search
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PorFndQryParams
{
    /**
     * Resulting list type
     *
     * - 'S': Short
     *
     * @var string
     */
    public $resultListType;

    /**
     * Code of language (ISO 639-1988).
     *
     * @var string
     */
    public $outputLanguage;

    /**
     * Max number of items to be returned
     *
     * @var int
     */
    public $resultMaxItems;

    /**
     * Index of item
     *
     * @var int
     */
    public $indexOfItem;

    /**
     * Category code
     *
     * @var string[] Up to 3 elements
     */
    public $targetCategoryCode = [];

    /**
     * The center of the search area is defined by latitude / longitude
     *
     * @var GeoCode
     */
    public $geoCode;

    /**
     * The center of the search area is defined by a POR identified by business id
     *
     * @var BusinessId
     */
    public $businessId;

    /**
     * POR internal key
     *
     * @var string
     */
    public $porId;

    /**
     * Default search radius for PORs in this primary category
     *
     * @var int
     */
    public $radius;

    /**
     * Geographic area filter
     *
     * @var Area
     */
    public $area;

    /**
     * Name filter
     *
     * @var Name
     */
    public $name;

    /**
     * Search type
     *
     * @var string
     */
    public $searchType;

    /**
     * @param string $targetCategoryCode The primary Target Category Code
     */
    public function __construct($targetCategoryCode = null)
    {
        if ($targetCategoryCode !== null) {
            $this->targetCategoryCode[] = $targetCategoryCode;
        }
    }
}
