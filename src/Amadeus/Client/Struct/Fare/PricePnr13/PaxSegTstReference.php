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

namespace Amadeus\Client\Struct\Fare\PricePnr13;

use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;

/**
 * PaxSegTstReference
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PaxSegTstReference
{
    /**
     * @var ReferenceDetails[]
     */
    public $referenceDetails = [];

    /**
     * PaxSegTstReference constructor.
     *
     * @param PaxSegRef[]|null $references New segment references format
     * @param array|null $segmentReference Legacy segment references format
     */
    public function __construct($references = null, $segmentReference = null)
    {
        if (!empty($references)) {
            foreach ($references as $ref) {
                $this->referenceDetails[] = new ReferenceDetails($ref->reference, $ref->type);
            }
        }

        //Support for legacy segment reference format - to be removed when breaking BC.
        if (!empty($segmentReference)) {
            foreach ($segmentReference as $segNum => $segQual) {
                $this->referenceDetails[] = new ReferenceDetails($segNum, $segQual);
            }
        }
    }
}
