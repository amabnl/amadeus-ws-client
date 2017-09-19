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

namespace Amadeus\Client\Struct\PointOfRef;

use Amadeus\Client\RequestOptions\PointOfRefSearchOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\PointOfRef\Search\Area;
use Amadeus\Client\Struct\PointOfRef\Search\BusinessId;
use Amadeus\Client\Struct\PointOfRef\Search\GeoCode;
use Amadeus\Client\Struct\PointOfRef\Search\Name;
use Amadeus\Client\Struct\PointOfRef\Search\PorFndQryParams;

/**
 * PointOfRef_Search request message structure
 *
 * @package Amadeus\Client\Struct\PointOfRef
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Search extends BaseWsMessage
{
    /**
     * @var PorFndQryParams
     */
    public $porFndQryParams;

    /**
     * Search constructor.
     *
     * @param PointOfRefSearchOptions $params
     */
    public function __construct(PointOfRefSearchOptions $params)
    {
        $this->loadBasic($params);
        $this->loadGeoCode($params);
        $this->loadCountryStateIata($params);
        $this->loadBusinessId($params);
    }

    /**
     * @param PointOfRefSearchOptions $params
     */
    protected function loadBasic($params)
    {
        $this->porFndQryParams = new PorFndQryParams($params->targetCategoryCode);

        if ($params->listType !== null) {
            $this->porFndQryParams->resultListType = $params->listType;
        }

        if ($params->searchRadius !== null) {
            $this->porFndQryParams->radius = $params->searchRadius;
        }

        if ($params->name !== null) {
            $this->porFndQryParams->name = new Name($params->name);
        }

        if ($params->maxNrOfResults !== null) {
            $this->porFndQryParams->resultMaxItems = $params->maxNrOfResults;
        }
    }

    /**
     * Search by geocode?
     *
     * @param PointOfRefSearchOptions $params
     */
    protected function loadGeoCode(PointOfRefSearchOptions $params)
    {
        if ($this->checkAllNotEmpty($params->latitude, $params->longitude)) {
            $this->porFndQryParams->geoCode = new GeoCode(
                $params->longitude,
                $params->latitude
            );
        }
    }

    /**
     * Search by country/state/iata code?
     *
     * @param PointOfRefSearchOptions $params
     */
    protected function loadCountryStateIata(PointOfRefSearchOptions $params)
    {
        if ($this->checkAnyNotEmpty($params->country, $params->state, $params->iata)) {
            $this->porFndQryParams->area = new Area(
                $params->country,
                $params->state,
                $params->iata
            );
        }
    }

    /**
     * Search by Business ID?
     *
     * @param PointOfRefSearchOptions $params
     */
    protected function loadBusinessId(PointOfRefSearchOptions $params)
    {
        if ($this->checkAnyNotEmpty($params->businessCategory, $params->businessForeignKey)) {
            $this->porFndQryParams->businessId = new BusinessId(
                $params->businessCategory,
                $params->businessForeignKey
            );
        }
    }
}
