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
 * Predicate
 *
 * @package Amadeus\Client\RequestOptions\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Predicate extends LoadParamsFromArray
{
    /**
     * Indicates the type of the predicate.
     *
     * Up to 11 Predicate Details can be provided.
     *
     * The first one will be placed in <predicateDetails><selectionDetails>,
     * any others will be placed in <predicateDetails><otherSelectionDetails>
     *
     * @var PredicateDetail[]
     */
    public $details = [];

    /**
     * To specify a minimum range
     *
     * @var int
     */
    public $rangeMin;

    /**
     * To specify a maximum range
     *
     * @var int
     */
    public $rangeMax;

    /**
     * To specify the elements to include (or exclude) in the history filtering
     *
     * @var PredicateType[]
     */
    public $types = [];

    /**
     * Retrieve history by free text (up to 199 alphanumeric characters)
     *
     * @var string
     */
    public $freeText;
}
