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
 * Reference
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Reference
{
    const QUAL_FORM_OF_PAYMENT_LINE = "FPL";
    const QUAL_FORM_OF_PAYMENT_TATTOO = "FPT";
    const QUAL_SALES_FORM_OF_PAYMENT_TATTOO = "SFPT";
    const QUAL_FP_ASSOCIATED_TO_TSM_TATTOO = "TSM";
    const QUAL_TRANSITIONAL_STORED_TICKET = "TST";

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $qualifier;

    /**
     * @var string|int
     */
    public $number;

    /**
     * Reference constructor.
     *
     * @param string $qualifier
     * @param string|int $number
     */
    public function __construct($qualifier = null, $number = null)
    {
        $this->qualifier = $qualifier;
        $this->number = $number;
    }
}
