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

namespace Amadeus\Client\Struct\Info;

/**
 * RequestOption
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RequestOption
{
    /**
     * @var SelectionDetails
     */
    public $selectionDetails;

    /**
     * @var SelectionDetails[]
     */
    public $otherSelectionDetails = [];

    /**
     * RequestOption constructor.
     *
     * @param string|null $optionInfo SelectionDetails::OPTINF_*
     * @param string|null $option SelectionDetails::OPT_*
     */
    public function __construct($optionInfo = null, $option = null)
    {
        if (!is_null($optionInfo) && !is_null($option)) {
            $this->selectionDetails = new SelectionDetails($optionInfo, $option);
        }
    }
}
