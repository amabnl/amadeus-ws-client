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

use Amadeus\Client\RequestOptions\DocRefund\MonetaryData;
use Amadeus\Client\Struct\Fop\MonetaryDetails as FopMonetaryDetails;

/**
 * MonetaryDetails
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MonetaryDetails extends FopMonetaryDetails
{
    const TYPE_AMOUNT_ENTERED_BY_AGENT = "AEA";
    const TYPE_BASE_FARE = "B";
    const TYPE_CANCELLATION_PENALTY = "CP";
    const TYPE_CURENCY_CONVERSION_AMOUNT = "CUR";
    const TYPE_DISCOUNT_AMOUNT_STORED_IN_REFUND_PANEL = "DA";
    const TYPE_DISCOUNT_AMOUNT_COMPUTED_BY_ATC_REFUND = "DAA";
    const TYPE_FARE_REFUND = "FRF";
    const TYPE_GST_TAX_AMOUNT = "GST";
    const TYPE_FARE_PAID_IN_MILES = "MFP";
    const TYPE_FARE_REFUND_IN_MILES = "MFR";
    const TYPE_FARE_USED_IN_MILES = "MFU";
    const TYPE_REFUND_TOTAL_IN_MILES = "MRT";
    const TYPE_NET_FARE_PAID = "NFP";
    const TYPE_NET_FARE_REFUND = "NFR";
    const TYPE_NET_FARE_USED = "NFU";
    const TYPE_NET_REMIT_TOTAL_AMOUNT = "NR";
    const TYPE_NO_SHOW_FEE = "NSF";
    const TYPE_PUBLISHED_FARE_PAID = "PFP";
    const TYPE_PUBLISHED_FARE_REFUND = "PFR";
    const TYPE_PUBLISHED_FARE_USED = "PFU";
    const TYPE_REFUND_TOTAL = "RFT";
    const TYPE_FARE_USED = "RFU";
    const TYPE_MISCELLANEOUS_FEE = "RMF";
    const TYPE_SELLING_FARE_PAID = "SFP";
    const TYPE_SELLING_FARE_REFUND = "SFR";
    const TYPE_SELLING_FARE_USED = "SFU";
    const TYPE_TOTAL_TAXES = "TXT";

    /**
     * MonetaryDetails constructor.
     *
     * @param MonetaryData $data
     */
    public function __construct(MonetaryData $data)
    {
        parent::__construct($data->amount, $data->currency, $data->type);
    }
}
