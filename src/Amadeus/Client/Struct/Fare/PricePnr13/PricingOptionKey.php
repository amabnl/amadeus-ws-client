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

/**
 * PricingOptionKey
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr13
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class PricingOptionKey
{
    const OPTION_NO_OPTION = "NOP";

    const OPTION_VALIDATING_CARRIER = "VC";

    const OPTION_FARE_CURRENCY_OVERRIDE = "FCO";

    const OPTION_PASSENGER_DISCOUNT = "PAX";

    const OPTION_FARE_BASIS_SIMPLE_OVERRIDE = "FBA";

    const OPTION_NEGOTIATED_FARES = "RN";

    /**
     * AC	Add Country taxes
     * AT	Add Tax
     * AWD	AWarD
     * BK	Booking class override
     * BND	Bound Input
     * CC	Controlling Carrier Override
     * CMP	Companions
     * CON	Connection
     * DAT	past DATe pricing
     * DIA	Diagnostic Tool
     * DO	booking Date Override
     * ET	Exempt Taxes
     * FBA	Fare BAsis simple override
     * FBL	Fare Basis force override
     * FBP	Force Break Point
     * FCO	Fare Currency Override
     * FCS	Fare Currency Selection
     * FOP	Form Of Payment
     * GRI	Global Route Indicator
     * IP	Instant Pricing
     * MA	Mileage Accrual
     * MBT	Fare amount override with M/BT
     * MC	Miles and Cash (Pricing by Points)
     * MIT	Fare amount override with M/IT
     * NBP	No BreakPoint
     * NF	No ticketing Fee
     * NOP	No Option
     * OBF	OB Fees (include and/or exclude)
     * PAX	Passenger discount/PTC
     * PFF	Pricing by Fare Family
     * PL	Pricing Logic
     * POS	Point Of Sale
     * POT	Point Of Ticketing override
     * PRM	expanded PaRaMeters
     * PRO	Promo Certificate
     * PTA	Point of Turnaround
     * PTC	PTC only
     * RC	Corporate negociated fares
     * RLI	Return LIst of fare
     * RLO	Return LOwest possible fare
     * RN	Negociated fare
     * RP	Published Fares
     * RU	Unifares
     * RW	Corporate Unifares
     * SEL	Passenger/Segment/Line/TST selection
     * STO	Stopover
     * TKT	TicKet Type
     * TRS	Transitional Certificate
     * VC	Validating Carrier
     * WC	Withold Country taxes
     * WQ	Withold Q surcharges
     * WT	Withold Tax
     * ZAP	ZAP-off
     *
     * @var string
     */
    public $pricingOptionKey;

    /**
     * PricingOptionKey constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->pricingOptionKey = $key;
    }
}
