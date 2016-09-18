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

namespace Amadeus\Client\Struct\Info;

use Amadeus\Client\RequestCreator\MessageVersionUnsupportedException;
use Amadeus\Client\RequestOptions\InfoEncodeDecodeCityOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Info_EncodeDecodeCity
 *
 * @package Amadeus\Client\Struct\Info
 */
class EncodeDecodeCity extends BaseWsMessage
{
    /**
     * @var LocationInformation
     */
    public $locationInformation;

    /**
     * @var RequestOption
     */
    public $requestOption;

    /**
     * @var Language
     */
    public $language;

    /**
     * @var CountryStateRestriction
     */
    public $countryStateRestriction;

    /**
     * EncodeDecodeCity constructor.
     *
     * @param InfoEncodeDecodeCityOptions $params
     * @throws MessageVersionUnsupportedException
     */
    public function __construct(InfoEncodeDecodeCityOptions $params)
    {
        if (!empty($params->locationCode)) {
            $this->locationInformation = new LocationInformation(
                LocationInformation::TYPE_LOCATION,
                strtoupper($params->locationCode),
                null
            );
        } elseif (!empty($params->locationName)) {
            //Only allow lowercase input for phonetic searches
            if ($params->searchMode !== InfoEncodeDecodeCityOptions::SEARCHMODE_PHONETIC) {
                $params->locationName = strtoupper($params->locationName);
            }

            $this->locationInformation = new LocationInformation(
                LocationInformation::TYPE_ALL,
                null,
                $params->locationName
            );
        }

        if (!empty($params->searchMode)) {
            $this->requestOption = new RequestOption(
                $params->searchMode,
                SelectionDetails::OPT_SEARCH_ALGORITHM
            );
        }

        if (!empty($params->selectResult)) {
            $selDet = new SelectionDetails(
                $params->selectResult,
                SelectionDetails::OPT_LOCATION_TYPE
            );

            if (!($this->requestOption instanceof RequestOption)) {
                $this->requestOption = new RequestOption();
                $this->requestOption->selectionDetails = $selDet;
            } else {
                $this->requestOption->otherSelectionDetails[] = $selDet;
            }
        }

        if (!is_null($params->restrictCountry) || !is_null($params->restrictState)) {
            $params->restrictState = $this->upperOrNull($params->restrictState);

            $params->restrictCountry = $this->upperOrNull($params->restrictCountry);

            $this->countryStateRestriction = new CountryStateRestriction(
                $params->restrictCountry,
                $params->restrictState
            );
        }
    }

    /**
     * Converts string to uppercase or null if null.
     *
     * @param string|null $param
     * @return string|null
     */
    protected function upperOrNull($param)
    {
        return (!is_null($param)) ? strtoupper($param) : $param;
    }
}
