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

namespace Amadeus\Client\Struct\Pnr\Retrieve;

use Amadeus\Client\Struct\Pnr\AddMultiElements\BoardOffPointDetail;
use Amadeus\Client\Struct\Pnr\AddMultiElements\Product;
use Amadeus\Client\Struct\Pnr\AddMultiElements\ProductDetails;

/**
 * ProductInformation
 *
 * @package Amadeus\Client\Struct\Pnr\Retrieve
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ProductInformation
{
    /**
     * @var Product
     */
    public $product;

    /**
     * @var BoardOffPointDetail
     */
    public $boardpointDetail;

    /**
     * @var BoardOffPointDetail
     */
    public $offpointDetail;

    /**
     * @var Company
     */
    public $company;

    /**
     * @var ProductDetails
     */
    public $productDetails;

    /**
     * ProductInformation constructor.
     *
     * @param \DateTime|null $departureDate
     * @param string|null $company
     * @param string|null $flightNumber
     */
    public function __construct($departureDate, $company, $flightNumber)
    {
        if ($departureDate instanceof \DateTime) {
            $this->product = new Product($departureDate);
        }

        if ($company) {
            $this->company = new Company($company);
        }

        if ($flightNumber) {
            $this->productDetails = new ProductDetails($flightNumber);
        }
    }
}
