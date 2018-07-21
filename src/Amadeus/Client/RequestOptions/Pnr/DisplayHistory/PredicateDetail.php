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

namespace Amadeus\Client\RequestOptions\Pnr\DisplayHistory;

use Amadeus\Client\LoadParamsFromArray;

/**
 * PredicateDetail
 *
 * @package Amadeus\Client\RequestOptions\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PredicateDetail extends LoadParamsFromArray
{
    const OPT_DISCARD_HISTORY_MATCHING_CRITERION = "FIL";
    const OPT_KEEP_HISTORY_MATCHING_CRITERION = "SEL";
    const OPT_FIND_HISTORY_CONTAINING_FREETEXT = "FND";
    const OPT_DISPLAY_HISTORY_BY_ELEMENT_TYPE = "EMS";
    const OPT_DISPLAY_HISTORY_WITH_QUEUEING_UPDATES = "QUE";
    const OPT_DISPLAY_ENVELOPES_CONTAINING_RF_LINE_ONLY = "KRF";
    const OPT_DISPLAY_ELEMENT_NUMBERING_SENT_IN_FEEDS = "FED";
    const OPT_DISPLAY_ALL_TTR_LINES = "TTR";
    const OPT_DISPLAY_ONLY_PNR_UPDATES = "PNR";
    const OPT_DISPLAY_ONLY_ETR_UPDATES = "ETR";
    const OPT_RETURN_FULL_DISPLAY_HISTORY_LINES = "FUL";

    /**
     * Can be used in combination of all values in the Option element.
     */
    const ASSOC_OPT_PREDICATE_TYPE = 0;

    /**
     * Can be used only in combination of the "QUE" value in the Option element.
     */
    const ASSOC_OPT_MATCH_QUEUE_UPDATE = 1;

    /**
     * self::OPT_*
     *
     * @var string
     */
    public $option;

    /**
     * self::ASSOC_OPT_*
     *
     * @var int
     */
    public $associatedOption;
}
