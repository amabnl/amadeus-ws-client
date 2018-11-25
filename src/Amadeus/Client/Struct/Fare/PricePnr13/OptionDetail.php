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

/**
 * OptionDetail
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author  Dieter Devlieghere <dermikagh@gmail.com>
 */
class OptionDetail
{
    /**
     * @var CriteriaDetails[]
     */
    public $criteriaDetails = [];

    /**
     * OptionDetail constructor.
     *
     * @param string|array|null $options
     */
    public function __construct($options = null)
    {
        if ($options instanceof CriteriaDetails) {
            $this->criteriaDetails[] = $options;
        } elseif (is_string($options)) {
            $this->criteriaDetails[] = new CriteriaDetails($options);
        } elseif (is_array($options)) {
            foreach ($options as $option) {
                if ($option instanceof CriteriaDetails) {
                    $this->criteriaDetails[] = $option;
                } elseif (is_array($option)) {
                    foreach ($option as $optionType => $optionDescription) {
                        $this->criteriaDetails[] = new CriteriaDetails($optionType, $optionDescription);
                    }
                } else {
                    $this->criteriaDetails[] = new CriteriaDetails($option);
                }
            }
        }
    }
}
