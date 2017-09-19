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

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareCheckRulesOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * CheckRules
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CheckRules extends BaseWsMessage
{
    /**
     * @var MsgType
     */
    public $msgType;

    /**
     * @var mixed
     */
    public $availcabinStatus;

    /**
     * @var mixed
     */
    public $conversionRate;

    /**
     * @var mixed
     */
    public $pricingTickInfo;

    /**
     * @var mixed
     */
    public $multiCorporate;

    /**
     * @var CheckRules\ItemNumber
     */
    public $itemNumber;

    /**
     * @var mixed
     */
    public $dateOfFlight;

    /**
     * @var CheckRules\FlightQualification[]
     */
    public $flightQualification = [];

    /**
     * @var mixed
     */
    public $transportInformation;

    /**
     * @var mixed
     */
    public $tripDescription;

    /**
     * @var mixed
     */
    public $pricingInfo;

    /**
     * @var CheckRules\FareRule
     */
    public $fareRule;

    /**
     * Construct new FareCheckRules request object
     *
     * msgType is automatically initialized
     *
     * @param FareCheckRulesOptions $params
     */
    public function __construct(FareCheckRulesOptions $params)
    {
        $this->msgType = new MsgType(MessageFunctionDetails::FARE_DISPLAY_RULES);

        if ($this->checkAnyNotEmpty($params->recommendations, $params->fareComponents)) {
            $this->itemNumber = new CheckRules\ItemNumber(
                $params->recommendations,
                $params->fareComponents
            );
        }

        if ($params->categoryList === true) {
            $this->flightQualification[] = new CheckRules\FlightQualification(
                CheckRules\DiscountDetails::FAREQUAL_RULE_CATEGORIES
            );
        } elseif (!empty($params->categories)) {
            $this->fareRule = new CheckRules\FareRule($params->categories);
        }
    }
}
