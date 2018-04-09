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
 * TransactionDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TransactionDetails
{
    /**
     * Perform Authorization on Ticket & MCO/EMD
     */
    const TRANS_AUTH_ON_TICKET_MCO_EMD = "DEF";
    /**
     * Perform Authorization on MCO/EMD
     */
    const TRANS_AUTH_ON_MCO_EMD = "DEFM";
    /**
     * Perform Authorization on Ticket
     */
    const TRANS_AUTH_ON_TICKET = "DEFP";
    /**
     * DEFX Transaction
     */
    const TRANS_DEFX = "DEFX";
    /**
     * Create form of payment only
     */
    const TRANS_CREATE_FORM_OF_PAYMENT = "FP";

    const INDICATOR_OB_FEES_CALCULATION = "O";

    /**
     * TRANS_*
     *
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $issueIndicator;

    /**
     * TransactionDetails constructor.
     *
     * @param string $code self::TRANS_*
     */
    public function __construct($code)
    {
        $this->code = $code;
    }
}
