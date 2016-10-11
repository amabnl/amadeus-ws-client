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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Pnr\Element\ServiceRequest as ServiceRequestOptions;

/**
 * Ssr
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 */
class Ssr
{
    /**
     * Holds confirmed
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
     * OTHS / DOCS / ....
     *
     * @var string
     */
    public $type;

    /**
     * self::STATUS_*
     *
     * @var string
     */
    public $status;

    /**
     * Amount
     *
     * @var int
     */
    public $quantity;

    /**
     * Airline or other provider code.
     *
     * @var string
     */
    public $companyId;

    /**
     *
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
    public $boardpoint;

    /**
     * @var string
     */
    public $offpoint;

    /**
     * Max 2 elements
     *
     * @var string[]
     */
    public $freetext = [];


    /**
     * Ssr constructor.
     *
     * @param ServiceRequestOptions|null $options
     */
    public function __construct(ServiceRequestOptions $options = null)
    {
        if (!is_null($options)) {
            $this->status = $options->status;
            $this->type = $options->type;
            $this->quantity = $options->quantity;
            $this->companyId = $options->company;
            $this->indicator = $options->indicator;
            $this->boardpoint = $options->boardPoint;
            $this->offpoint = $options->offPoint;
            $this->freetext = $options->freeText;
        }
    }
}
