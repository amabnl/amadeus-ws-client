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

use Amadeus\Client\RequestOptions\DocRefundInitRefundOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * DocRefund_InitRefund request structure
 *
 * @package Amadeus\Client\Struct\DocRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InitRefund extends BaseWsMessage
{
    /**
     * @var TicketNumberGroup
     */
    public $ticketNumberGroup;

    /**
     * @var ActionDetails
     */
    public $actionDetails;

    /**
     * @var ItemNumberGroup
     */
    public $itemNumberGroup;

    /**
     * @var mixed
     */
    public $currencyOverride;

    /**
     * @var mixed
     */
    public $stockProviderDetails;

    /**
     * @var mixed
     */
    public $targetOfficeDetails;

    /**
     * @var mixed
     */
    public $transactionContext;

    /**
     * InitRefund constructor.
     *
     * @param DocRefundInitRefundOptions $options
     */
    public function __construct($options)
    {
        if (!empty($options->ticketNumber)) {
            $this->ticketNumberGroup = new TicketNumberGroup($options->ticketNumber);
        }

        if (!empty($options->actionCodes)) {
            $this->actionDetails = new ActionDetails($options->actionCodes);
        }
        
        if ($this->checkAnyNotEmpty($options->stockTypeCode, $options->stockProvider)) {
            $this->stockProviderDetails = new StockProviderDetails($options->stockTypeCode, $options->stockProvider);
        }

        if ($this->checkAnyNotEmpty($options->itemNumber, $options->itemNumberType, $options->couponNumber)) {
            $this->itemNumberGroup = new ItemNumberGroup(
                $options->itemNumber,
                $options->itemNumberType,
                $options->couponNumber
            );
        }
    }
}
