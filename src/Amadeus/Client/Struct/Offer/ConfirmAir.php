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

use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Offer_ConfirmAirOffer
 *
 * @package Amadeus\Client\Struct\Offer
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConfirmAir extends BaseWsMessage
{
    /**
     * Offer tattoo to be confirmed
     *
     * @var OfferTattoo
     */
    public $offerTatoo;

    /**
     * Group for pax reconciliation between Pax PNR and passenger types prices at offer creation time
     *
     * @var PassengerReassociation[]
     */
    public $passengerReassociation = [];

    /**
     * @param OfferConfirmAirOptions|null $options
     */
    public function __construct($options = null)
    {
        if (!is_null($options)) {
            $this->offerTatoo = new OfferTattoo($options->tattooNumber);

            foreach ($options->passengerReassociation as $reAssoc) {
                $this->passengerReassociation[] = new PassengerReassociation(
                    $reAssoc->pricingReference,
                    $reAssoc->paxReferences
                );
            }
        }
    }
}
