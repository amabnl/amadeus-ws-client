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

namespace Amadeus\Client\Struct\Fare\PricePnr13;

use Amadeus\Client\RequestOptions\Fare\PricePnr\ObFee;
use Amadeus\Client\RequestOptions\Fare\PricePnr\ZapOff;

/**
 * PenDisInformation
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PenDisInformation
{
    const QUAL_PENALTY = 700;
    const QUAL_DISCOUNT = 701;
    const QUAL_OB_FEES = "OBF";
    const QUAL_ZAPOFF_DISCOUNT = "ZAP";

    /**
     * self::QUAL_*
     *
     * @var string
     */
    public $discountPenaltyQualifier;

    /**
     * @var DiscountPenaltyDetails[]
     */
    public $discountPenaltyDetails = [];

    /**
     * PenDisInformation constructor.
     *
     * @param string|null $discountPenaltyQualifier
     * @param mixed $data
     */
    public function __construct($discountPenaltyQualifier, $data)
    {
        $this->discountPenaltyQualifier = $discountPenaltyQualifier;

        switch ($discountPenaltyQualifier) {
            case PenDisInformation::QUAL_OB_FEES:
                $this->loadObFees($data);
                break;
            case PenDisInformation::QUAL_DISCOUNT:
                $this->loadPaxDiscounts($data);
                break;
            case PenDisInformation::QUAL_ZAPOFF_DISCOUNT:
                $this->loadZapOffDiscounts($data);
                break;
        }
    }

    /**
     * @param ObFee[] $obFees
     */
    protected function loadObFees($obFees)
    {
        foreach ($obFees as $obFee) {
            $amountType = (!empty($obFee->amount)) ?
                DiscountPenaltyDetails::AMOUNTTYPE_FIXED_WHOLE_AMOUNT : DiscountPenaltyDetails::AMOUNTTYPE_PERCENTAGE;

            $rate = (!empty($obFee->amount)) ? $obFee->amount : $obFee->percentage;

            $this->discountPenaltyDetails[] = new DiscountPenaltyDetails(
                $obFee->rate,
                self::makeObFeeFunction($obFee->include),
                $amountType,
                $rate,
                $obFee->currency
            );
        }
    }

    /**
     * @param string[] $discounts
     */
    protected function loadPaxDiscounts($discounts)
    {
        foreach ($discounts as $discount) {
            $this->discountPenaltyDetails[] = new DiscountPenaltyDetails($discount);
        }
    }

    /**
     * @param $zapOffs ZapOff[]
     */
    protected function loadZapOffDiscounts($zapOffs)
    {
        foreach ($zapOffs as $zapOff) {
            $amountType = (isset($zapOff->amount)) ?
                DiscountPenaltyDetails::AMOUNTTYPE_FIXED_WHOLE_AMOUNT : DiscountPenaltyDetails::AMOUNTTYPE_PERCENTAGE;

            $rate = (isset($zapOff->amount)) ? $zapOff->amount : $zapOff->percentage;

            $this->discountPenaltyDetails[] = new DiscountPenaltyDetails(
                $zapOff->rate,
                $zapOff->applyTo,
                $amountType,
                $rate
            );
        }
    }

    /**
     * Make the correct function code
     *
     * @param bool $include
     * @return string
     */
    protected static function makeObFeeFunction($include)
    {
        return ($include === true) ? ObFee::FUNCTION_INCLUDE : ObFee::FUNCTION_EXCLUDE;
    }
}
