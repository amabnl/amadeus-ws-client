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

namespace Amadeus\Client\Struct\Offer;

use Amadeus\Client\RequestOptions\Offer\PassengerDef;

/**
 * PassengerReassociation
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PassengerReassociation
{
    /**
     * @var PricingRecordId
     */
    public $pricingRecordId;

    /**
     * @var PaxReference
     */
    public $paxReference;

    /**
     * @param string|null $pricingRef
     * @param PassengerDef[]|null $paxRefs
     */
    public function __construct($pricingRef = null, $paxRefs = null)
    {
        if (!is_null($pricingRef)) {
            $this->pricingRecordId = new PricingRecordId($pricingRef);
        }

        if (is_array($paxRefs)) {
            $this->paxReference = new PaxReference($paxRefs);
        }
    }
}
