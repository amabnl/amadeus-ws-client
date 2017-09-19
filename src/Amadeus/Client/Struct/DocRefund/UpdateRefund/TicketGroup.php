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

namespace Amadeus\Client\Struct\DocRefund\UpdateRefund;

use Amadeus\Client\RequestOptions\DocRefund\TickGroupOpt;
use Amadeus\Client\Struct\Ticket\CheckEligibility\ActionIdentification;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * TicketGroup
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TicketGroup extends WsMessageUtility
{
    /**
     * @var CouponInformationDetails
     */
    public $couponInformationDetails;

    /**
     * @var BoardingPriority
     */
    public $boardingPriority;

    /**
     * @var ActionIdentification
     */
    public $actionIdentification;

    /**
     * @var ReferenceInformation
     */
    public $referenceInformation;

    /**
     * TicketGroup constructor.
     *
     * @param TickGroupOpt $opt
     */
    public function __construct(TickGroupOpt $opt)
    {
        if ($this->checkAnyNotEmpty($opt->couponNumber, $opt->couponStatus)) {
            $this->couponInformationDetails = new CouponInformationDetails(
                $opt->couponNumber,
                $opt->couponStatus
            );
        }

        if (!empty($opt->boardingPriority)) {
            $this->boardingPriority = new BoardingPriority($opt->boardingPriority);
        }

        if (!empty($opt->actionRequestCode)) {
            $this->actionIdentification = new ActionIdentification($opt->actionRequestCode);
        }

        if (!empty($opt->references)) {
            $this->referenceInformation = new ReferenceInformation(
                $opt->references,
                ReferenceDetails::TYPE_VALIDATION_CERTIFICATE_USED_FOR_STAFF
            );
        }
    }
}
