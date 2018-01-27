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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

/**
 * Passenger - Structure class for the passenger message part for PNR_AddMultiElements messages
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Passenger extends \Amadeus\Client\Struct\Pnr\Retrieve\Passenger
{
    const PASST_ADULT = "ADT";
    const PASST_INFANT = "INF";
    const PASST_CHILD = "CHD";

    /**
     * 1: Infant (INF) No more info in Edifact.
     */
    const INF_NOINFO = 1;
    /**
     * 2: Infant given name only (INF/BILL) Infant given name will be placed
     * in a 2nd occurence of C324 of this (adult) passenger TIF.
     * The 2nd C324/6353 element will contain INF.
     */
    const INF_GIVEN = 2;
    /**
     * 3: Infant given and last name (INFGATES/BILL) Infant is treated as a
     * separate TIF following immediately this (adult) passenger TIF.
     * This following TIF C324/6353 element will contain INF.
     */
    const INF_FULL = 3;
    
    /*
     * self::PASST_*
     *
     * Passenger Types:
     * 766     Infant without seat
     * 767     Infant with seat
     * A00     Companion
     * ACC     Accompanied Person
     * ACP     Test data
     * ADT     Adult
     * AGT     Agent
     * ASB     Adult Standby
     * ASF     Air-Sea Fare
     * BAG     Excess Baggage
     * BKB     Bookable Staff Travel
     * BLD     Blind Passenger
     * BRV     Bereavement
     * BUD     Airline Buddy Standby Fare
     * C       CBBG - Cabin Baggage
     * C00     Accompanied Child
     * CBC     (no description)
     * CBI     (no description)
     * CHD     Child
     * CLG     Clergy
     * CMA     Adult with Companion
     * CMM     Commuter
     * CMP     Companion
     * CMX     ANN - CMP - CMA - Blended
     * CNE     (no description)
     * CNV     Convention
     * COR     CORPORATE NAME
     * CPN     Coupon discount PSGR
     * CTZ     Category Z PSGR
     * DOD     DEPT of Defense PSGR
     * E       EXST - Extra Seat
     * E00     Group Inclusive tour Child
     * ECH     (no description)
     * EDT     (no description)
     * EMI     Emigrant
     * ENF     (no description)
     * EPA     (no description)
     * EPC     (no description)
     * EPI     (no description)
     * F00     Family Plan Child
     * FDT     Head of Family
     * FFY     Frequent Flyer
     * FIF     (no description)
     * FTF     Frequent Flyer
     * G       Group
     * G00     Group Child
     * GCF     Government Contract.
     * GCT     City/Council Government PSGR
     * GDP     Government Employee Dependent
     * GEX     Government Exchange PSGR
     * GGZ     (no description)
     * GIT     Group Inclusive Tour PSGR
     * GMZ     GOV / CT / MIL / CATZ
     * GOV     GVT Blended
     * GRP     Group Passenger
     * GST     State Government
     * GTF     Goverment Travel
     * GVM     GOV / GOVCT / CATZ
     * GVT     Government Travel
     * GVZ     Government / GOVCT / CATZ
     * H00     Children Charter
     * HCR     Adult Charter
     * HOF     Head of Family
     * I00     Individual Inclusive Tour Child
     * ICP     Incentive Certificates
     * IIT     Individual Inclusive Tour PSGR
     * INE     (no description)
     * INF     Infant not Occupying a Seat
     * INS     Infant Occupying a Seat
     * INY     (no description)
     * ITX     Individual Inclusive Tour PSGR
     * J00     (no description)
     * JCB     (no description)
     * JNF     (no description)
     * JNS     (no description)
     * JOB     Job Corps
     * LBR     Laborer / Worker
     * LUV     (no description)
     * M00     Military Child
     * MBT     Military - basic Training Graduate
     * MCR     Military Charter
     * MDP     Spouse and Dependent Children of Military Personnel
     * MED     Patients Travel for Medical treatment
     * MFM     Military Immediate family
     * MIL     Military Confirmed passenger
     * MIR     Military Reserve on Active Duty
     * MIS     Missionary.
     * MLZ     Military / Cat. Z
     * MPA     Military parents/Parents in Law
     * MRE     Retired military and Dependent
     * MSB     Military Standby
     * MSG     Multi State Government
     * MTH     Month
     * MUS     Military DOD based in Usa
     * MXS     Military DOD not based in USA
     * NAT     NATO
     * NSB     Non Revenue Standby
     * NTL     (no description)
     * OTS     PSGR occupying two Seats
     * P00     Children Charter
     * PAX     Passenger
     * PCA     (no description)
     * PCB     (no description)
     * PCR     Adult Charter
     * PFA     Private Fare
     * PFB     Private Fare
     * PFC     Private Fare
     * PFD     Private Fare
     * PFE     Private Fare
     * PFF     Private Fare
     * PFG     Private Fare
     * PFH     Private Fare
     * PFI     Private Fare
     * PFJ     Private Fare
     * PFK     Private Fare
     * PFL     Private Fare
     * PFM     Private Fare
     * PFN     Private Fare
     * PFO     Private Fare
     * PFP     Private Fare
     * PFQ     Private Fare
     * PFR     Private Fare
     * PFS     Private Fare
     * PFT     Private Fare
     * PFU     Private Fare
     * PFV     Private Fare
     * PFW     Private Fare
     * PFX     Private Fare
     * PFY     Private Fare
     * PFZ     Private Fare
     * PIF     (no description)
     * PIL     (no description)
     * PSP     (no description)
     * PST     (no description)
     * REC     Military Recruit
     * REF     Refugee
     * S00     Senior Citizen
     * SBY     Standby Staff Travel
     * SDB     Student Standby
     * SEA     Seaman
     * SPS     Spouse
     * SRC     Senior Citizen
     * STR     State Resident
     * STU     Student
     * T00     Frequent Flyer Child
     * TIM     (no description)
     * TUR     Tour Conductor
     * U00     Unaccompanied Child
     * UAM     Unaccompanied Minor
     * UDA     (no description)
     * UDN     (no description)
     * UNV     University Employee
     * V00     Visit Another Country Child
     * VAC     Visit Another Country Adult
     * VAG     Group Visit Another Country Adult
     * VFR     Visit friends Relatives
     * WCR     (no description)
     * WEB     (no description)
     * Y00     Government Travel Child
     * YCB     Senior Citizen Standby
     * YCD     Senior Citizen
     * YCL     Clergy
     * YCR     Youth Charter
     * YMZ     Category Z passenger
     * YRS     Year
     * YSB     Youth Standby
     * YTH     Youth Confirmed
     * Z00     Group Visit Another Country,Child
     *
     * @var string
    */
    public $type;
    
    /*
     * self::INF_*
     *
     * @var string|int
     */
    public $infantIndicator;

    /**
     * @var string
     */
    public $identificationCode;

    /**
     * Passenger constructor.
     *
     * @param $firstName
     * @param $type
     */
    public function __construct($firstName, $type)
    {
        parent::__construct($firstName);
        $this->type = $type;
    }
}
