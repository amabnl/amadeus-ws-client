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

use Amadeus\Client\Struct\Ticket\RepricePnrWithBookingClass\CouponDetails as RepriceCouponDetails;

/**
 * CouponDetails
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CouponDetails extends RepriceCouponDetails
{
    const COUPON_1 = 1;
    const COUPON_2 = 2;
    const COUPON_3 = 3;
    const COUPON_4 = 4;

    const STATUS_CONFIRMED = "OK";
    const STATUS_OPEN = "OPE";
    const STATUS_REFUNDED = "RF";
    const STATUS_TICKETED = "T";
    const STATUS_VOID = "V";

    /**
     * self::STATUS_*
     *
     * @var string
     */
    public $cpnStatus;

    /**
     * @var string
     */
    public $settlementAuthorization;

    /**
     * CouponDetails constructor.
     *
     * @param int|string|null $cpnNumber self::COUPON_*
     * @param string|null $cpnStatus self::STATUS_*
     */
    public function __construct($cpnNumber = null, $cpnStatus = null)
    {
        $this->cpnStatus = $cpnStatus;
        parent::__construct($cpnNumber);
    }
}
