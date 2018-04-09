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

namespace Amadeus\Client\Struct\Queue;

/**
 * SubQueueInfoDetails
 *
 * @package Amadeus\Client\Struct\Queue
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SubQueueInfoDetails
{
    /**
     * Identification Type: Category
     *
     * See Amadeus Core WS Documentation
     * [Identification type, coded codesets (Ref: 9893 IA 02.2.14)]
     * @var string
     */
    const IDTYPE_CATEGORY = "C";

    /**
     * @var string
     */
    public $identificationType;
    /**
     * @var string
     */
    public $itemNumber;
    /**
     * @var string
     */
    public $itemDescription;

    /**
     * @param string $categoryNumber
     * @param string|null $type
     */
    public function __construct($categoryNumber, $type)
    {
        $this->identificationType = $type;
        $this->itemNumber = $categoryNumber;
    }
}
