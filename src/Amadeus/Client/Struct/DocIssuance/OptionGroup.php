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

namespace Amadeus\Client\Struct\DocIssuance;

use Amadeus\Client\RequestOptions\DocIssuance\Option;

/**
 * OptionGroup
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OptionGroup
{
    /**
     * @var Switches
     */
    public $switches;

    /**
     * @var SubCompoundOptions[]
     */
    public $subCompoundOptions = [];

    /**
     * @var OverrideAlternativeDate
     */
    public $overrideAlternativeDate;

    /**
     * OptionGroup constructor.
     *
     * @param string|Option $option
     */
    public function __construct($option)
    {
        if ($option instanceof Option) {
            $this->switches = new Switches($option->indicator);
            $this->subCompoundOptions[] = new SubCompoundOptions(
                $option->subCompoundType,
                $option->subCompoundDescription
            );
        } else {
            $this->switches = new Switches($option);
        }
    }
}
