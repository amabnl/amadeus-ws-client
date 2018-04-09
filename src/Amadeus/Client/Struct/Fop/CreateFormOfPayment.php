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

use Amadeus\Client\RequestOptions\FopCreateFopOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\BestEffort;
use Amadeus\Client\Struct\Fop\CreateFormOfPayment\FopGroup14;

/**
 * FOP_CreateFormOfPayment message structure version 15 and up
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CreateFormOfPayment extends BaseWsMessage
{
    /**
     * @var TransactionContext
     */
    public $transactionContext;

    /**
     * @var BestEffort[]
     */
    public $bestEffort = [];

    /**
     * @var ReservationControlInformation
     */
    public $reservationControlInformation;

    /**
     * @var FopGroup[]
     */
    public $fopGroup = [];

    /**
     * FOP_CreateFormOfPayment constructor.
     *
     * @param FopCreateFopOptions $options
     */
    public function __construct(FopCreateFopOptions $options)
    {
        if ($this->checkAnyNotEmpty($options->transactionCode, $options->obFeeCalculation)) {
            $this->transactionContext = new TransactionContext(
                $options->transactionCode,
                $options->obFeeCalculation
            );
        }

        if ($this->checkAllNotEmpty($options->bestEffortAction, $options->bestEffortIndicator)) {
            $this->bestEffort[] = new BestEffort($options->bestEffortIndicator, $options->bestEffortAction);
        }

        $this->loadFopGroup($options);
    }

    /**
     * Load fopGroup
     *
     * @param FopCreateFopOptions $options
     */
    protected function loadFopGroup(FopCreateFopOptions $options)
    {
        foreach ($options->fopGroup as $group) {
            if ($this instanceof CreateFormOfPayment14) {
                $this->fopGroup[] = new FopGroup14($group);
            } else {
                $this->fopGroup[] = new FopGroup($group);
            }
        }
    }
}
