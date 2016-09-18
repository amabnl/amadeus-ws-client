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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;

/**
 * ServiceRequest element (SSR)
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class ServiceRequest extends Element
{
    /**
     * Hold confirmed
     */
    const STATUS_HOLD_CONFIRMED = "HK";
    /**
     * Sold (on free sales basis)
     */
    const STATUS_SOLD_FREE_SALES = "FS";
    /**
     * Sold
     */
    const STATUS_SOLD = "SS";
    /**
     * Have requested
     */
    const STATUS_REQUESTED = "HN";
    /**
     * Confirming
     */
    const STATUS_CONFIRMING = "KK";
    /**
     * Need. Reply required indicating action taken using appropriate code
     */
    const STATUS_NEED = "NN";

    /**
     * OTHS / DOCS / ...
     *
     * @var string
     */
    public $type;

    /**
     * self::STATUS_*
     *
     * FS Sold (on free sales basis)
     * HK Holds confirmed
     * HN Have requested
     * KK Confirming
     * NN Need. Reply required indicating action taken using appropriate code
     * SS Sold
     *
     * @var string
     */
    public $status;

    /**
     * How many
     *
     * @var int
     */
    public $quantity;

    /**
     * 2-character company code
     *
     * The coded description of supplier of a service or product, i.e. airline designator code.
     *
     * @var string
     */
    public $company;

    /**
     * B Boarding pass may not be issued until
     * ICS Informational segment for codeshare
     * N No action required
     * NB No a boarding pass may not be issued
     * P01 Explosion indicator for SSR input
     * P02 Validated indicator of Frequent Flyer SSR
     * Y Yes a boarding pass may be issued
     *
     * @var string
     */
    public $indicator;

    /**
     * @var string
     */
    public $boardPoint;

    /**
     * @var string
     */
    public $offPoint;

    /**
     * Up to 2 elements
     *
     * @var string[]
     */
    public $freeText = [];
}
