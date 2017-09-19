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

namespace Amadeus\Client\Struct\Pnr\Cancel;

/**
 * Elements
 *
 * @package Amadeus\Client\Struct\Pnr\Cancel
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Elements
{
    const ENTRY_XD = "D";

    const ENTRY_ELEMENT = "E";

    const ENTRY_ITINERARY = "I";

    const ENTRY_NAME_INTEGRATION = "G";

    const ENTRY_PRIORITY_LINE = "P";

    const ENTRY_ES = "S";

    /**
     * self::ENTRYTYPE_*
     *
     * @var string
     */
    public $entryType;

    /**
     * @var Element[]
     */
    public $element = [];

    /**
     * Elements constructor.
     *
     * @param string $entryType self::ENTRY_*
     */
    public function __construct($entryType)
    {
        $this->entryType = $entryType;
    }
}
