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

namespace Amadeus\Client\RequestOptions;

/**
 * Pnr_DisplayHistory Request Options
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrDisplayHistoryOptions extends Base
{
    /**
     * Standard history
     */
    const OPT_STANDARD = 'S';

    /**
     * Filters out redundant elements from history
     */
    const OPT_FILTER_REDUNDANT = 'F';

    /**
     * History option
     *
     * self::OPT_*
     *
     * @var string
     */
    public $option = self::OPT_STANDARD;

    /**
     * PNR record locator to retrieve history for.
     *
     * @var string
     */
    public $recordLocator;

    /**
     * Optional creation date (& optionally time) of the PNR you're trying to retrieve
     *
     * If the time component is 00:00:00, it will not be included in the request.
     *
     * @var \DateTime
     */
    public $pnrCreateTime;

    /**
     * Specify up to 20 filter options in order to filter the PNR history lines that will be returned.
     *
     * There are different types of filter options: they can be used to select (or exclude)
     * certain history lines matching a criterion.
     *
     * @var Pnr\DisplayHistory\Predicate[]
     */
    public $predicates = [];

    /**
     * The maximum number of history items to retrieve.
     *
     * @var int
     */
    public $scrollingMax;
}
