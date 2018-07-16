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

namespace Amadeus\Client\RequestOptions;

/**
 * Air_SellFromRecommendation Request options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AirSellFromRecommendationOptions extends Base
{
    const ALG_CANCEL_IF_UNSUCCESSFUL = "M1";
    const ALG_BOOK_IF_UNSUCCESSFUL = "M2";

    /**
     * self::ALG_*
     *
     * M1 Trigger Sell Optimization Algorithm, option cancel all if unsuccessful.
     * M2 Trigger Sell Optimization Algorithm, option keep all confirmed if unsuccessful.
     *
     * @var string
     */
    public $algorithm = self::ALG_CANCEL_IF_UNSUCCESSFUL;

    /**
     * @var Air\SellFromRecommendation\Itinerary[]
     */
    public $itinerary = [];
}
