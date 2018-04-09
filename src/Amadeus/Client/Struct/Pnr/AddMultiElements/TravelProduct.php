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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

/**
 * TravelProduct
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TravelProduct
{
    /**
     * date and time details
     *
     * @var Product
     */
    public $product;

    /**
     * boardpoint details
     *
     * @var BoardOffPointDetail
     */
    public $boardpointDetail;

    /**
     * offpoint details
     *
     * @var BoardOffPointDetail
     */
    public $offpointDetail;

    /**
     * airline or system code
     *
     * @var Company
     */
    public $company;

    /**
     * product details - number and class
     *
     * @var ProductDetails
     */
    public $productDetails;

    /**
     * Product type details. Here: availability context.
     *
     * @var FlightTypeDetails
     */
    public $flightTypeDetails;

    /**
     * B Boarding pass may not be issued until
     * N No action required
     * NB No a boarding pass may not be issued
     * P01 Explosion indicator for SSR input
     * P02 Validated indicator of Frequent Flyer SSR
     * Y Yes a boarding pass may be issued
     *
     * @var
     */
    public $processingIndicator;

    /**
     * TravelProduct constructor.
     *
     * @param \DateTime|null $date
     * @param string|null $departureCode
     * @param string|null $company
     */
    public function __construct($date = null, $departureCode = null, $company = null)
    {
        if ($date instanceof \DateTime) {
            $this->product = new Product($date);
        }

        if (!empty($departureCode)) {
            $this->boardpointDetail = new BoardOffPointDetail($departureCode);
        }

        if (!empty($company)) {
            $this->company = new Company($company);
        }
    }
}
