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

namespace Amadeus\Client\Struct\Fare\PricePnr12;

/**
 * AttributeDetails
 *
 * @package Amadeus\Client\Struct\Fare\PricePnr12
 * @author dieter <dermikagh@gmail.com>
 */
class AttributeDetails
{
    const OVERRIDE_NO_OPTION = 'NOP';
    const OVERRIDE_FAREBASIS = 'FBA';
    const OVERRIDE_FARETYPE_PUB = 'RP';
    const OVERRIDE_FARETYPE_NEG = 'RN';
    const OVERRIDE_FARETYPE_UNI = 'RU';
    const OVERRIDE_FARETYPE_CORPNR = 'RC';
    const OVERRIDE_FARETYPE_CORPUNI = 'RW';
    const OVERRIDE_RETURN_LOWEST = 'RLO';
    const OVERRIDE_RETURN_ALL = 'RLI';
    const OVERRIDE_PASSENGER_TYPE_CODE = 'PTC';
    const OVERRIDE_ELECTRONIC_TICKET = 'ETK';
    const OVERRIDE_PAPER_TICKET = 'PAT';
    const OVERRIDE_BOTH_TICKET = 'EP';

    /**
     * BK Booking code override
     * CMP Companions
     * DIA Diagnostic tool
     * EP Electronic Paper Ticket
     * ETK Electronic Ticket
     * FBA Automated Pricing By Fare Basis (no override)
     * FBL Automated Pricing By Fare Basis (override)
     * MA Mileage Accrual
     * MBT Bulk Tour
     * MC Pricing by Points
     * MIT Inclusive Tour
     * NOP No option.
     * PAF Pay As you Fly option
     * PAT Paper Ticket
     * PRM Extended parameters
     * PRO Promo Certificate
     * PTC Price at specified passenger only
     * RC Corporate number
     * RLI Return list of all possible fares
     * RLO Return lowest fare from the list
     * RN Negotiated fares
     * RP Published fares
     * RU Unifares
     * RUL Rule override requested
     * RW Corporate unifares
     * TRP Trap option requested
     * TRS Transitional Certificate
     * WQ Withhold surcharges
     *
     * @var string
     */
    public $attributeType;
    /**
     * @var string
     */
    public $attributeDescription;

    /**
     * AttributeDetails constructor.
     *
     * @param string $attributeType
     * @param string|null $attributeDescription
     */
    public function __construct($attributeType, $attributeDescription = null)
    {
        $this->attributeType = $attributeType;
        $this->attributeDescription = $attributeDescription;
    }
}
