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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;

/**
 * ServiceRequest element (SSR)
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ServiceRequest extends Element
{
    /**
     * Hold confirmed
     */
    const STATUS_HOLD_CONFIRMED = "HK";
    /**
     * Sold (on free sales basis)
     */
    const STATUS_SOLD_FREE_SALES = "FS";
    /**
     * Sold
     */
    const STATUS_SOLD = "SS";
    /**
     * Have requested
     */
    const STATUS_REQUESTED = "HN";
    /**
     * Confirming
     */
    const STATUS_CONFIRMING = "KK";
    /**
     * Need. Reply required indicating action taken using appropriate code
     */
    const STATUS_NEED = "NN";

    /**
     * Service Request code
     *
     * FQTS Frequent flyer service request
     * FQTR Frequent flyer mileage program redemption
     * FPML Fruit platter
     * EXST Extra seat
     * DEPU Deportee, unaccompanied
     * DEPA Deportee accompanied by an escort
     * DEAF Deaf ( with or without guide dog)
     * DBML Diabetic meal
     * COUR Commercial courier
     * CKIN Information for airport personnel
     * CHML Child meal
     * CBBG Cabin baggage
     * BULK Bulky baggage
     * BSCT Bassinet/Carry cot/Baby basket.
     * FRAV First available
     * FRAG Fragile baggage
     * FQTV Frequent flyer mileage program accrual
     * FQTU Frequent flyer upgrade and accrual
     * HMFL High fiber meal
     * GRPS Passengers travelling together using a common identity.
     * GRPF Group fare
     * LFML Low cholesterol
     * LCML Low calorie meal
     * LANG Languages spoken
     * KSML Kosher meal
     * HNML Hindu meal
     * MOML Moslem meal
     * MEDA Medical case
     * MAAS Meet and assist
     * LSML Low sodium, no salt added meal
     * LPML Low protein meal
     * NSST No smoking seat
     * NSSB No smoking bulkhead seat
     * NSSA No smoking aisle seat
     * NLML Non lactose meal
     * NAME Name - when airline holds reservations under a different name
     * PETC Animal in cabin
     * PCTC Emergency contact details
     * OTHS Other service not specified by any other SSR code
     * ORML Oriental meal
     * NSSW No smoking window seat
     * SMSW Smoking window seat
     * SMST Smoking seat
     * SMSB Smoking bulkhead seat
     * SMSA Smoking aisle seat
     * SLPR Bed/Berth in cabin
     * SFML Sea food meal
     * SEMN Seaman - ship's crew
     * SEAT Pre-reserved seat with boarding pass issued or to be issued
     * RVML Raw vegetarian meal
     * RQST Seat request - include seat number preference
     * PSPT Passport
     * PRML Low Purim meal
     * TWOV Transit or transfer without visa
     * TKTL Ticket time limit
     * TKNM Ticket number in FH element
     * TKNC Ticket number in transmission
     * TKNA Ticket number in FA element
     * STCR Stretcher passenger
     * SPML Special meal
     * SPEQ Sports equipment
     * XBAG Excess baggage
     * WCOB Wheelchair - on board
     * WCMP Wheelchair - manual power (US carriers only)
     * WCHS Wheel chair up and down steps
     * WCHR Wheelchair - for ramp
     * WCHC Wheelchair - all the way to seat
     * WCBW Wheelchair
     * WCBD Wheelchair - dry cell battery
     * VLML Vegetarian meal (lacto-ovo)
     * VGML Vegetarian meal (non-diary)
     * UMNR Unaccompanied minor
     *
     * @var string
     */
    public $type;

    /**
     * self::STATUS_*
     *
     * FS Sold (on free sales basis)
     * HK Holds confirmed
     * HN Have requested
     * KK Confirming
     * NN Need. Reply required indicating action taken using appropriate code
     * SS Sold
     *
     * @var string
     */
    public $status;

    /**
     * How many
     *
     * @var int
     */
    public $quantity;

    /**
     * 2-character company code
     *
     * The coded description of supplier of a service or product, i.e. airline designator code.
     *
     * @var string
     */
    public $company;

    /**
     * B Boarding pass may not be issued until
     * ICS Informational segment for codeshare
     * N No action required
     * NB No a boarding pass may not be issued
     * P01 Explosion indicator for SSR input
     * P02 Validated indicator of Frequent Flyer SSR
     * Y Yes a boarding pass may be issued
     *
     * @var string
     */
    public $indicator;

    /**
     * @var string
     */
    public $boardPoint;

    /**
     * @var string
     */
    public $offPoint;

    /**
     * Up to 2 elements
     *
     * @var string[]
     */
    public $freeText = [];
}
