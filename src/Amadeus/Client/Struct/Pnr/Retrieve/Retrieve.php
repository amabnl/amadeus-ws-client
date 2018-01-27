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

/**
 * Structure class for the Retrieve message part for PNR_Retrieve messages
 *
 * @package Amadeus\Client\Struct\Pnr\Retrieve
 * @author  Dieter Devlieghere <dermikagh@gmail.com>
 */
class Retrieve
{
    /**
     * @var string|int
     */
    public $type;

    /**
     * @var string
     */
    public $service;

    /**
     * @var string
     */
    public $tattoo;

    /**
     * @var string
     */
    public $office;

    /**
     * @var string
     */
    public $targetSystem;

    /**
     * @var string
     */
    public $option1;

    /**
     * @var string
     */
    public $option2;

    /**
     * Construct retrieve element
     *
     * @param string|int $retrievalType
     * @param string $office
     * @param string[] $options
     */
    public function __construct($retrievalType, $office, $options)
    {
        $this->type = $retrievalType;
        $this->office = $office;

        foreach ($options as $key => $option) {
            if ($key === 0) {
                $this->option1 = $option;
            } elseif ($key === 1) {
                $this->option2 = $option;
            }
        }
    }
}
