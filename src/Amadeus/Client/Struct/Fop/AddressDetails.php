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
 * AddressDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AddressDetails
{
    const FORMAT_UNSTRUCTURED = 5;

    /**
     * @var int
     */
    public $format = self::FORMAT_UNSTRUCTURED;

    /**
     * @var string
     */
    public $line1;

    /**
     * @var string
     */
    public $line2;

    /**
     * @var string
     */
    public $line3;

    /**
     * @var string
     */
    public $line4;

    /**
     * @var string
     */
    public $line5;

    /**
     * @var string
     */
    public $line6;


    /**
     * AddressDetails constructor.
     *
     * @param string[] $addressLines
     */
    public function __construct($addressLines)
    {
        foreach ($addressLines as $key => $line) {
            $lineNumProp = 'line'.($key + 1);
            if (property_exists($this, $lineNumProp)) {
                $this->$lineNumProp = $line;
            }
        }
    }
}
