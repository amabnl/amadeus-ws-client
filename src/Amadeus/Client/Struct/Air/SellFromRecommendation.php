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

namespace Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * SellFromRecommendation
 *
 * @package Amadeus\Client\Struct\Air
 * @author dieter <dermikagh@gmail.com>
 */
class SellFromRecommendation extends BaseWsMessage
{
    /**
     * @var MessageActionDetails
     */
    public $messageActionDetails;

    public $originatorDetails;

    public $recordLocator;

    public $frequentFlyerData;

    /**
     * @var ItineraryDetails[]
     */
    public $itineraryDetails = [];

    /**
     * SellFromRecommendation constructor.
     *
     * @param AirSellFromRecommendationOptions $options
     */
    public function __construct(AirSellFromRecommendationOptions $options)
    {
        $this->messageActionDetails = new MessageActionDetails(
            MessageFunctionDetails::MSGFUNC_LOWEST_FARE
        );
        $this->messageActionDetails->messageFunctionDetails->additionalMessageFunction = $options->algorithm;

        foreach ($options->itinerary as $itinerary) {
            $this->itineraryDetails[] = new ItineraryDetails($itinerary);
        }
    }
}
