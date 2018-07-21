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

namespace Amadeus\Client\Struct\Pnr\DisplayHistory;

/**
 * PredicateSelectionDetails
 *
 * @package Amadeus\Client\Struct\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PredicateSelectionDetails
{
    /**
     * Display ETR elements only
     */
    const OPT_DISPLAY_ETR_ONLY = "ETR";
    /**
     * Display element numbering sent in Feeds
     */
    const OPT_DISPLAY_EL_NUMBERING_FEEDS = "FED";
    /**
     * Find history lines containing a certain freetext
     */
    const OPT_FIND_HISTORY_LINES_FREETEXT = "FND";
    /**
     * Display full display of history lines
     */
    const OPT_FULL_DISPLAY = "FUL";
    /**
     * Filter predicate
     */
    const OPT_FILTER_PREDICATE = "FIL";
    /**
     * Display envelopes containing RF line only
     */
    const OPT_DISP_ENVELOPES_CONTAINING_RF = "KRF";
    /**
     * Display PNR elements only
     */
    const OPT_DISP_PNR_ELEMENTS = "PNR";
    /**
     * Match queue update
     */
    const OPT_MATCH_QUEUE_UPDATE = "QUE";
    /**
     * Selection predicate
     */
    const OPT_SELECTION_PREDICATE = "SEL";
    /**
     * Display all TTR elements
     */
    const OPT_DISPLAY_ALL_TTR = "TTR";

    /**
     * Predicate Type Option Information
     */
    const OPTINF_PREDICATE_TYPE = 0;
    /**
     * Match Queue Updates Option Information
     */
    const OPTINF_MATCH_QUEUE_UPDATES = 1;

    /**
     * self::OPT_*
     *
     * @var string
     */
    public $option;

    /**
     * self::OPTINF_*
     *
     * @var int
     */
    public $optionInformation;

    /**
     * PredicateSelectionDetails constructor.
     *
     * @param string $option self::OPT_*
     * @param int $optionInformation self::OPTINF_*
     */
    public function __construct($option, $optionInformation = self::OPTINF_PREDICATE_TYPE)
    {
        $this->option = $option;
        $this->optionInformation = $optionInformation;
    }
}
