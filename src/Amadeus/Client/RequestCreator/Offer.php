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

namespace Amadeus\Client\RequestCreator;

use Amadeus\Client\RequestOptions\OfferConfirmAirOptions;
use Amadeus\Client\RequestOptions\OfferConfirmCarOptions;
use Amadeus\Client\RequestOptions\OfferConfirmHotelOptions;
use Amadeus\Client\RequestOptions\OfferCreateOptions;
use Amadeus\Client\RequestOptions\OfferVerifyOptions;
use Amadeus\Client\Struct;

/**
 * Offer
 *
 * @package Amadeus\Client\RequestCreator
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Offer
{
    /**
     * Offer_VerifyOffer
     *
     * @param OfferVerifyOptions $params
     * @return Struct\Offer\Verify
     */
    public function createOfferVerifyOffer(OfferVerifyOptions $params)
    {
        $req = new Struct\Offer\Verify(
            $params->offerReference,
            $params->segmentName
        );

        return $req;
    }

    /**
     * @param OfferConfirmAirOptions $params
     * @return Struct\Offer\ConfirmAir
     */
    public function createOfferConfirmAirOffer(OfferConfirmAirOptions $params)
    {
        return new Struct\Offer\ConfirmAir($params);
    }


    /**
     * Offer_ConfirmHotelOffer
     *
     * @param OfferConfirmHotelOptions $params
     * @return Struct\Offer\ConfirmHotel
     */
    public function createOfferConfirmHotelOffer(OfferConfirmHotelOptions $params)
    {
        return new Struct\Offer\ConfirmHotel($params);
    }

    /**
     * @param OfferConfirmCarOptions $params
     * @return Struct\Offer\ConfirmCar
     */
    public function createOfferConfirmCarOffer(OfferConfirmCarOptions $params)
    {
        return new Struct\Offer\ConfirmCar($params);
    }

    /**
     * Offer_CreateOffer
     *
     * Ok, I just realised this function name is a bit confusing. Sorry.
     *
     * @param OfferCreateOptions $params
     * @return Struct\Offer\Create
     */
    public function createOfferCreateOffer(OfferCreateOptions $params)
    {
        return new Struct\Offer\Create($params);
    }
}
