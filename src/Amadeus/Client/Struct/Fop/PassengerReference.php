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

namespace Amadeus\Client\Struct\Fop;

/**
 * PassengerReference
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PassengerReference
{
    const TYPE_INFANT = "INF";
    const TYPE_ADULT = "PAX";

    const TYPE_STAKEHOLDER_PAYER = "SHP";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var string|int
     */
    public $value;

    /**
     * PassengerReference constructor.
     *
     * @param string $type self::TYPE_*
     * @param int|string $value
     */
    public function __construct($type, $value)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
