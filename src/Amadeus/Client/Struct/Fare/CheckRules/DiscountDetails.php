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

namespace Amadeus\Client\Struct\Fare\CheckRules;

/**
 * DiscountDetails
 *
 * @package Amadeus\Client\Struct\Fare\CheckRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DiscountDetails
{
    const FAREQUAL_RULE_CATEGORIES = '764';

    /**
     * Possible values:
     *
     * 700 Add-on origin
     * 701 Add-on destination
     * 702 Advances purchase period
     * 703 Air Taxis - Exempt or non-exempt
     * 704 All carriers
     * 705 (Base) fares without taxes
     * 706 (Base) fares with partial taxes
     * 707 (Base) Fares with taxes included
     * 708 Blackout
     * 709 Cancellation fee details
     * 70A Base
     * 70B Total
     * 70C No seasonal or blackout restrictions
     * 70D Rule provisions vary by travel date
     * 710 Carrier Exemption - To indicate whether a carrier is exempt
     * 711 Charters - Exempt or non-exempt
     * 712 Circle trip
     * 713 Connection cities for which joint fares exist
     * 714 Country Exemption - To indicate whether a country is exempt
     * 715 Exclude infant fares
     * 716 Fare amount (for mileage fare input amount)
     * 717 Fare amount conversion from information
     * 718 Fare amount conversion to information
     * 719 Fare Cannot be used for Pricing
     * 720 Fare Discount before Tax
     * 721 Fare Discount after tax
     * 722 Fare display with rules
     * 723 Fare is add-on constructed
     * 724 Fare is mileage based
     * 725 Fare is routing based
     * 726 Fare restricted to certain days
     * 727 Fare restricted to certain flights
     * 728 Fare restricted to certain times
     * 729 Fares with a fixed penalty not greater than given amount or percent
     * 730 Fares with no penalties
     * 731 Fares with penalties
     * 732 Fares with no advance purchase restrictions
     * 733 Half round trip
     * 734 IATA participant fares for specific carrier(s)
     * 735 Include domestic tax request
     * 736 Indicates fare is refundable
     * 737 Indicates no refund restriction
     * 738 Indicates no cancellation rules are held
     * 739 Indicates no time of day restrictions
     * 740 Indicates percentage of the fare that is non-refundable
     * 741 Indicates the fare is non-refundable
     * 742 Indicates the rule should be consulted
     * 743 Joint fares or rules
     * 744 Long display
     * 745 Minimum stay in days
     * 746 Maximum stay in days
     * 747 Minimum flight range number
     * 748 Maximum flight range number
     * 749 Negotiated fares
     * 750 No Advanced Purchase Restrictions
     * 751 No maximum stay restrictions
     * 752 No minimum or maximum restrictions
     * 753 No minimum stay restrictions
     * 754 No restrictions
     * 755 No VUSA fares
     * 756 One way
     * 757 One way, cannot be doubled
     * 758 Private fares
     * 759 Restrictions apply
     * 760 Return route number
     * 761 Return travel must start by this date
     * 762 Round the world
     * 763 Round trip
     * 764 Rule list display
     * 765 Rule number
     * 766 Seasonal
     * 767 Seasonal applies to outbound travel
     * 768 Seasonal applies to inbound travel
     * 769 Specified fare or route
     * 770 Surcharge data
     * 771 Suppress companion fares
     * 772 Sort fares low to high
     * 773 Sort fares high to low
     * 774 Suppress airport sort (by city pair)
     * 775 Ticket only on this date
     * 776 Ticket on or after this date
     * 777 Ticket on or before this date
     * 778 Total Tax
     * 779 Travel day indicator
     * 780 Travel must begin within time span shown
     * 781 Travel must be entirely within the time span shown
     * 782 Travel to be completed by given date
     * 783 Travel to be started by given date
     * 784 Unsaleable fares
     * 785 Validated fares according to bilateral agreement
     * 786 Travel effective on or after date
     * 787 Fare canceled on this date
     * 788 Modified fare
     * 789 Ticket/travel future effective date change
     * 790 Fare only priceable on the effective date
     * 791 Fare only priceable after the effective date
     * 792 Same day effective date change
     * 793 Round trip only -- cannot be divided to calculate one way fare
     * 794 Maximum stay in months
     * 795 Tickets are non-refundable
     * 796 Tickets are non-refundable after departure
     * 797 Penalties applies
     * 798 Subject to cancellation/change penalty
     * 799 Tickets are non-refundable before departure
     *
     * @var string
     */
    public $fareQualifier;
    /**
     * @var string
     */
    public $rateCategory;
    /**
     * @var double
     */
    public $amount;
    /**
     * @var double
     */
    public $percentage;

    /**
     * DiscountDetails constructor.
     *
     * @param string|null $discountQualifier
     */
    public function __construct($discountQualifier = null)
    {
        $this->fareQualifier = $discountQualifier;
    }
}
