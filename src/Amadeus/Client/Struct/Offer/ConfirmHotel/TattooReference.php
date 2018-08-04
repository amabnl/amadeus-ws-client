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

namespace Amadeus\Client\Struct\Offer\ConfirmHotel;

/**
 * TattooReference
 *
 * @package Amadeus\Client\Struct\Offer\ConfirmHotel
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TattooReference
{
    const SEGMENT_NAME_AIR = "AIR";

    const SEGMENT_NAME_HOTEL_AUX = "HHL";

    /**
     *
     * 700 no active itinerary
     * 701 Coupon notification
     * 702 Active* (*To indicate the queue is designated for use whether it currently has any items.
     *              When not present, the default status is non-active)
     * 703 Queue placement is inhibited
     * 704 Queue level notification
     * 705 Queue being printed
     * 706 Sub-queue present
     * 707 On hold
     * A Add
     * AC Accrual
     * AL Aiport control
     * ALL Allocated
     * AVA Available
     * B Flown/used
     * BD Boarded
     * C Change
     * CK Checked in
     * CLO Closed
     * D Reprint
     * DB Deboarded
     * DN Denied boarding
     * E Exchanged/reissued
     * F Critical free text
     * G Non air segment
     * I Original Issue (Open for Use)
     * IF Information only
     * INU In use
     * IO Irregular operations
     * K Confirmed, effective, working, firm, etc
     * LIM Limitations on use
     * NAV Not available
     * NC Not checked in
     * NS Infant, no seat
     * OF Offloaded
     * OK Confirmed
     * OLD Replaced item
     * OPE Open
     * P Provisional, draft proposed subject to change, etc
     * PAV Partial Availability - Specified sub-elements only
     * PE Print Exchange
     * PR Printed
     * PRF Preferred
     * PRP Proposed/Intended Allocation
     * R Request
     * RD Redemption
     * REP Replacement
     * REV Revised
     * RF Refunded
     * RQ Requested
     * S Suspended
     * SA Space Available
     * SB Standby
     * SRV Serviceable
     * T Ticketed
     * UNS Unserviceable
     * V Void
     * WL Waitlisted
     * X Cancel
     *
     * @var string
     */
    public $status;

    /**
     * @var Reference
     */
    public $reference;

    /**
     * self::SEGMENT_NAME_*
     *
     * AIR Air segment
     * HHL Automated Hotel auxiliary segment
     *
     * @var string
     */
    public $segmentName;

    /**
     * TattooReference constructor.
     *
     * @param int $number
     * @param string $qualifier
     * @param string $segName
     */
    public function __construct(
        $number,
        $qualifier = Reference::QUAL_OFFER_TATTOO,
        $segName = self::SEGMENT_NAME_HOTEL_AUX
    ) {
        $this->reference = new Reference($number, $qualifier);
        $this->segmentName = $segName;
    }
}
