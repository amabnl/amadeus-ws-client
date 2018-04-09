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

use Amadeus\Client\RequestOptions\Pnr\Element\ManualCommission;

/**
 * Commission
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Commission
{
    /**
     * @var int|string
     */
    public $passengerType;

    /**
     * @var string
     */
    public $indicator;

    /**
     * @var CommissionInfo
     */
    public $commissionInfo;

    /**
     * @var CommissionInfo
     */
    public $oldCommission;

    /**
     * @var int
     */
    public $manualCapping;

    /**
     * Commission constructor.
     *
     * @param ManualCommission $options
     */
    public function __construct($options)
    {
        $this->passengerType = $options->passengerType;
        $this->indicator = $options->indicator;
        $this->commissionInfo = new CommissionInfo(
            $options->percentage,
            $options->amount,
            $options->vatIndicator,
            $options->remitIndicator
        );
    }
}
