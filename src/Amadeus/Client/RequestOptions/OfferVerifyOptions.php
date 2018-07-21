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
 * Offer_Verify Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OfferVerifyOptions extends Base
{
    const SEGMENT_AIR = 'AIR';
    const SEGMENT_CAR = 'CCR';
    const SEGMENT_HOTEL = 'HHL';

    /**
     * Reference to the offer nr.
     *
     * @var int
     */
    public $offerReference;

    /**
     * AIR, CCR, HHL
     *
     * https://webservices.amadeus.com/extranet/structures/viewMessageStructure.do?id=3979&serviceVersionId=3443&isQuery=true#
     *
     * Value Description
     * AIR  Air segment
     * CCR  Automated Car auxiliary segment
     * HHL  Automated Hotel auxiliary segment
     *
     * @var string
     */
    public $segmentName;
}
