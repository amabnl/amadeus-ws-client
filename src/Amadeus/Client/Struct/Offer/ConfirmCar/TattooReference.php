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

namespace Amadeus\Client\Struct\Offer\ConfirmCar;

use Amadeus\Client\Struct\Offer\Reference;

/**
 * TattooReference
 *
 * @package Amadeus\Client\Struct\Offer\ConfirmCar
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TattooReference
{
    const SEGNAME_CAR = "CCR";

    /**
     * @var Reference
     */
    public $reference;

    /**
     * AIR Air segment
     * CCR Automated Car auxiliary segment
     * HHL Automated Hotel auxiliary segment
     *
     * @var string
     */
    public $segmentName;

    /**
     * TattooReference constructor.
     *
     * @param int|string $carOfferTattoo
     * @param string $segmentName
     */
    public function __construct($carOfferTattoo, $segmentName = self::SEGNAME_CAR)
    {
        $this->reference = new Reference($carOfferTattoo);
        $this->segmentName = $segmentName;
    }
}
