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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\PnrDisplayHistoryOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Pnr\DisplayHistory\PnrInfo;
use Amadeus\Client\Struct\Pnr\DisplayHistory\Predicate;
use Amadeus\Client\Struct\Pnr\DisplayHistory\RedundantElements;
use Amadeus\Client\Struct\Pnr\DisplayHistory\ScrollingDetails;

/**
 * DisplayHistory
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DisplayHistory extends BaseWsMessage
{
    /**
     * @var DisplayHistory\ScrollingDetails
     */
    public $scrollingDetails;

    /**
     * @var DisplayHistory\PnrInfo
     */
    public $pnrInfo;

    /**
     * @var DisplayHistory\RedundantElements
     */
    public $redundantElements;

    /**
     * @var DisplayHistory\Predicate[]
     */
    public $predicate = [];

    /**
     * DisplayHistory constructor.
     *
     * @param PnrDisplayHistoryOptions $options
     */
    public function __construct(PnrDisplayHistoryOptions $options)
    {
        if (is_string($options->recordLocator)) {
            $this->pnrInfo = new PnrInfo(
                $options->recordLocator,
                $options->pnrCreateTime
            );
        }

        $this->redundantElements = new RedundantElements($options->option);

        if (is_int($options->scrollingMax)) {
            $this->scrollingDetails = new ScrollingDetails($options->scrollingMax);
        }

        foreach ($options->predicates as $predicateOptions) {
            $this->predicate[] = new Predicate($predicateOptions);
        }
    }
}
