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

namespace Amadeus\Client\Struct\Fare\PricePnr12;

use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

/**
 * DiscountInformation
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr12
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DiscountInformation
{
    /**
     * @var PenDisInformation
     */
    public $penDisInformation;

    /**
     * @var ReferenceQualifier
     */
    public $referenceQualifier;

    /**
     * DiscountInformation constructor.
     */

    /**
     * DiscountInformation constructor.
     *
     * @param string $qual
     * @param array $discounts
     * @param PaxSegRef[] $refs
     */
    public function __construct($qual, $discounts, $refs)
    {
        $this->penDisInformation = new PenDisInformation();
        $this->penDisInformation->infoQualifier = $qual;

        foreach ($discounts as $discountCode) {
            $this->penDisInformation->penDisData[] = new PenDisData($discountCode);
        }

        if (!empty($refs)) {
            $this->referenceQualifier = new ReferenceQualifier($refs);
        }
    }
}
