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

namespace Amadeus\Client\Struct\Service;

use Amadeus\Client\RequestOptions\ServiceBookPriceServiceOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Service\BookPriceService\Product;

/**
 * Service_BookPriceService request structure
 *
 * @package Amadeus\Client\Struct\Service
 * @author Mike Hernas <mike@ahoy.io>
 */
class BookPriceService extends BaseWsMessage
{
    /**
     * @var Product[]
     */
    public $Product;
    
    /**
     * @var int
     */
    public $Version = 0;

    /**
     * BookPriceService constructor.
     *
     * @param ServiceBookPriceServiceOptions $options
     */
    public function __construct($options)
    {
        foreach ($options->services as $service) {
            $this->Product[] = new Product($service);
        }
    }
}
