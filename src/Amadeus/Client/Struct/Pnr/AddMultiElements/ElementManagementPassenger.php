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
 * ElementManagementPassenger
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ElementManagementPassenger
{
    const SEG_NAME = "NM";

    const SEG_GROUPNAME = "NG";

    /**
     * @var Reference
     */
    public $reference;
    /**
     * self::SEG_*
     *
     * @var string
     */
    public $segmentName;

    /**
     * @param string|null $segmentName one of the defined constants SEG_* in this class
     */
    public function __construct($segmentName = null)
    {
        $this->segmentName = $segmentName;
    }
}
