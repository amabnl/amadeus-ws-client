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

namespace Amadeus\Client\Struct\Fop\CreateFormOfPayment;

/**
 * StatusInformation
 *
 * @package Amadeus\Client\Struct\Fop\CreateFormOfPayment
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StatusInformation
{
    const IND_CREATE_FOP_EVEN_IF_AUTHORIZATION_FAILED = "CFP";
    const IND_SPLIT_PRICING_RECORD = "SPT";

    const ACTION_CONFIRMATION = "KK";
    const ACTION_REFUSAL = "UU";

    /**
     * self::IND_*
     *
     * @var string
     */
    public $indicator;

    /**
     * self::ACTION_*
     *
     * @var string
     */
    public $action;

    /**
     * StatusInformation constructor.
     *
     * @param string $indicator
     * @param string $action
     */
    public function __construct($indicator, $action)
    {
        $this->indicator = $indicator;
        $this->action = $action;
    }
}
