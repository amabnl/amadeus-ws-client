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

use Amadeus\Client\RequestOptions\Fop\Payment;

/**
 * MonetaryInformation
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MonetaryInformation
{
    /**
     * @var MonetaryDetails
     */
    public $monetaryDetails;

    /**
     * @var MonetaryDetails[]
     */
    public $otherMonetaryDetails = [];

    /**
     * MonetaryInformation constructor.
     *
     * @param Payment[] $payments
     */
    public function __construct($payments)
    {
        foreach ($payments as $counter => $payment) {
            if ($counter === 0) {
                $this->monetaryDetails = new MonetaryDetails(
                    $payment->amount,
                    $payment->currency,
                    $payment->type
                );
            } else {
                $this->otherMonetaryDetails[] = new MonetaryDetails(
                    $payment->amount,
                    $payment->currency,
                    $payment->type
                );
            }
        }
    }
}
