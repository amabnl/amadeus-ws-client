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

namespace Amadeus\Client\Struct\DocRefund;

/**
 * StatusDetails
 *
 * @package Amadeus\Client\Struct\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class StatusDetails
{
    const INDICATOR_ATC_REFUND = "ATC";
    const INDICATOR_ATC_REFUND_INVOLUNTARY = "ATI";
    const INDICATOR_COVER_ADDITIONAL_EXPENDITURE = "COV";
    const INDICATOR_EMD_TICKET_NUMBER = "EMD";
    const INDICATOR_INVOLUNTARY_NO_REASON = "I";
    const INDICATOR_NON_REFUNDABLE_INDICATORS_BYPASS = "NRF";
    const INDICATOR_NOT_REPORTED_REFUND = "NRP";
    const INDICATOR_NO_SHOW = "NS";
    const INDICATOR_ZERO_REFUND = "NUL";
    const INDICATOR_HOLD_FOR_FUTURE_USE= "RTF";
    const INDICATOR_TAXES = "TAX";


    const INDICATOR_INHIBIT_REFUND_NOTICE = "IRN"; //DocRefund_ProcessRefund
    const INDICATOR_REFUND_REVIEW_OPTION = "REV"; //DocRefund_ProcessRefund

    /**
     * self::INDICATOR_*
     *
     * @var string
     */
    public $indicator;

    /**
     * StatusDetails constructor.
     *
     * @param string $option self::INDICATOR_*
     */
    public function __construct($option)
    {
        $this->indicator = $option;
    }
}
