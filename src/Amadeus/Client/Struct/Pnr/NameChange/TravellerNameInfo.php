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

namespace Amadeus\Client\Struct\Pnr\NameChange;

/**
 * TravellerNameInfo
 *
 * @package Amadeus\Client\Struct\Pnr\NameChange
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TravellerNameInfo
{
    /**
     * @var string|string
     */
    public $quantity;

    /**
     * Possible values:
     *
     * ADT  Adult
     * AGT  Agent
     * ASB  Adult Standby
     * ASF  Air-Sea Fare
     * BAG  Excess Baggage
     * BLD  Blind passenger
     * BRV  Bereavement
     * BUD  Airline Buddy Standby Fare
     * C00  Accompanied Child
     * CHD  Child
     * CLG  Clergy
     * CMA  Adult with companion
     * CMM  Commuter
     * CMP  Companion
     * CMX  ANN-CMP-CMA-Blended
     * CNV  Convention
     * CPN  coupon discount PSGR
     * CTZ  Category Z PSGR
     * DOD  DEPT of Defense PSGR
     * E    EXST - Extra Seat
     * E00  Group Inclusive tour Child
     * EMI  Emigrant
     * F00  Family Plan Child
     * FFY  Frequent Flyer
     * G00  Group Child
     * GCF  Government Contract.
     * GCT  City/Council Government PSGR
     * GDP  City/Council Government PSGR
     * GEX  Government Exchange PSGR
     * GIT  Group Inclusive Tour PSGR
     * GRP  Group Passenger
     * GSP  GROUP SCHOOL PARTY
     * GST  State Government
     * GVT  Government Travel
     * H00  Children Charter
     * HOF  Head of Family
     * I00  Individual Inclusive Tour Child
     * ICP  Incentive Certificates
     * IIT  Individual Inclusive Tour PSGR
     * INF  Infant not Occupying a Seat
     * INS  Infant Occupying a Seat
     * INY  INFANT ACCOMPANIED BY YOUTH
     * LBR  Laborer / Worker
     * M00  Military Child
     * MBT  Military - basic Training Graduate
     * MCR  Military Charter
     * MDP  Spouse and Dependent Children of Military Personnel
     * MED  Patients Travel for Medical treatment
     * MIL  Military Confirmed passenger
     * MIR  Military Reserve on Active Duty
     * MIS  Missionary.
     * MPA  Military parents/Parents in Law
     * MRE  Retired military and Dependent
     * MSB  Military Standby
     * MSG  Multi State Government
     * MUS  Military DOD based in Usa
     * MXS  Military DOD not based in USA
     * NAT  NATO
     * NSB  Non Revenue Standby
     * OTS  PSGR occupying two Seats
     * P00  Children Charter
     * PCR  Adult Charter
     * PIL  (no description)
     * REC  Military Recruit
     * REF  Refugee
     * S00  Senior Citizen
     * SEA  Seaman
     * SPA  SPOUSE - ACCOMPANIED
     * SPH  SPOUSE - HEAD OF FAMILY
     * SPS  Spouse
     * STR  State Resident
     * STU  Student
     * T00  Frequent Flyer Child
     * TUR  Tour Conductor
     * U00  Unaccompanied Child
     * UNV  University Employee
     * V00  Visit Another Country Child
     * VAC  Visit Another Country Adult
     * VFR  Visit friends Relatives
     * WEB  (no description)
     * Y00  Government Travel Child
     * YCB  Senior Citizen Standby
     * YCD  Senior Citizen
     * YCL  Clergy
     * YCR  Youth Charter
     * YSB  Youth Standby
     * YTH  Youth Confirmed
     * Z00  Group Visit Another Country,Child
     *
     * @var string
     */
    public $type;

    /**
     *
     * BKB  Bookable Staff Travel
     * SBY  Standby Staff Travel
     *
     * @var string
     */
    public $otherType;

    /**
     * @var int
     */
    public $uniqueCustomerIdentifier;

    /**
     * 1  Infant no surname and no given name
     * 2  Infant with given name only
     * 3  Infant with surname and given name
     *
     * @var string|int
     */
    public $infantIndicator;

    /**
     * @var string
     */
    public $travellerIdentificationCode;

    /**
     * @var int
     */
    public $age;

    /**
     * TravellerNameInfo constructor.
     *
     * @param int $identifier
     * @param string|null $type
     * @param string|int|null $infantIndicator
     */
    public function __construct($identifier, $type = null, $infantIndicator = null)
    {
        $this->quantity = 1;
        $this->uniqueCustomerIdentifier = $identifier;
        $this->type = $type;
        $this->infantIndicator = $infantIndicator;
    }
}
