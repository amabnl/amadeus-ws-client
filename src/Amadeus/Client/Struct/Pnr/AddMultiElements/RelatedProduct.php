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
 * RelatedProduct
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RelatedProduct
{
    const STATUS_CONFIRMED = "HK";

    const STATUS_WAITLIST_CLOSED = "HL";

    const STATUS_NEED_SEGMENT = "NN";

    const STATUS_GHOST_HOLD_CONFIRMED = "GK";

    const STATUS_GHOST_HOLD_WAITLISTED = "GK";

    const STATUS_GHOST_NEED = "GN";

    const STATUS_PASSIVE_CONFIRMED = "PK";

    const STATUS_PASSIVE_WAITLISTED = "PL";

    /**
     * @var int
     */
    public $quantity;

    /**
     * self::STATUS_*
     *
     * AG Space available for Group (SUA only)
     * CK Codeshare - Guaranteed Sell
     * CL Codeshare - Waitlist
     * CN Codeshare - Need
     * CS Codeshare - Sell
     * CX Codeshare - Cancellation
     * DK For PCA user instead of LK
     * DW For PCA user instead of LL
     * FB Forced booking (Amadeus carriers only)
     * FF Guaranteed Sell for passenger with Frequent Flyer number in PNR
     * FG Group Forced booking (Amadeus carriers only)
     * FN Forced request (no AVS check)
     * FR Forced request (Amadeus carriers only)
     * FS Forced Sale (Amadeus carriers only)
     * GI Group Interactive
     * GK Ghost Segment – Holding Confirmed
     * GL Ghost Segment – Holding Waitlist
     * GN Ghost need
     * GW Group priority waitlist interactive
     * ID Interline Discount Employees
     * IF Information only
     * IG Interline discount for Group (SUA only)
     * IN If not Holding, Need
     * IS If not holding, Sell
     * IW If not holding, waitlist
     * IX If Holding Cancel
     * KG Confirmed group (Space control agent)
     * LF Forced Waitlist (Amadeus carriers only) (class open = HK , waitlist closed = HL)
     * LG Forced Waitlist for Group (SUA only)
     * LK Guaranteed sell - Generated automatically by reservation system as a result of an
     *                      interactive session or when space has been sold from a direct access availability
     * LL Please waitlist
     * NA Next Available – Need the segment specified or the alternative segment immediately following.
     *                     Confirm the specified segment if available and take no action on the alternative.
     *                     If not available, reply with the appropriate action code and act upon the alternatives
     *                     in the same manner
     * NK Sold interactively (TTY) or sold from direct access, in PNR before reply message or in TTY out
     * NN Need Segment
     * NZ Non Commerical PNR sell
     * OK For PCA instead of LK
     * OX Conditional cancellation (for marriage only, interactive conversation)
     * PA Priority A Waitlist Request
     * PB Priority B Waitlist Request
     * PC Priority C Waitlist Request
     * PD Priority D Waitlist Request
     * PE Priority E Waitlist Request
     * PG Priority waitlist for group
     * PK Passive Segment, Confirmed
     * PL Passive Segment, Wait Listed
     * PN Equivalent to HN , used in system one
     * PX Passive segment cancellation
     * RG Request group space
     * RR Reconfirmation
     * SA Space Available (Amadeus carriers only)
     * SG Sell Group
     * SS Sold from Availability (link down) or against AVS
     * ST Stretcher
     * UM Unaccompanied minors
     * US Unable to accept sale. Flight, Class, Date, segment closed - Have waitlisted
     * UU Unable to accept sale. Flight, Class, Date, segment closed - Have waitlisted
     * XL Cancel Waitlisted segment
     * XR Cancellation recommended (reason provided in OSI)
     * XX Cancel confirmed/requested/waitlisted space
     *
     * @var string
     */
    public $status;

    /**
     * RelatedProduct constructor.
     *
     * @param string $status self::STATUS_*
     * @param int $quantity
     */
    public function __construct($status, $quantity = 1)
    {
        $this->status = $status;
        $this->quantity = $quantity;
    }
}
