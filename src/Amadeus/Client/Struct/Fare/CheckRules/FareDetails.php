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
 * FareDetails
 *
 * @package Amadeus\Client\Struct\Fare\CheckRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareDetails
{
    /**
     * Possible values:
     *
     * 700 Abonnement
     * 701 Accompanied child
     * 702 Accompanying adult
     * 703 Adult charter
     * 704 Agent discount
     * 705 Air/Sea fares
     * 706 Border Area (Argentina)
     * 707 Charter
     * 708 Charter - Adult
     * 709 Charter - Youth
     * 70A Total number of passengers
     * 70B Number of unique passenger types
     * 70C Total number of passenger types
     * 70D Total number of segments priced
     * 70E Number of stopovers applied to fare breakpoint
     * 70F Number of surcharges applied to fare breakpoint
     * 70G Total number of taxes
     * 70H Total number of passenger facility charges (PFC)
     * 70I Total number ZP
     * 70J Total number of fare calculation/surcharges charges
     * 70K Flight group number
     * 70L Tax
     * 70M Surcharges
     * 70N PFCs
     * 70O ZPs
     * 70P Number of unique PTCs
     * 70Q Total PTCs
     * 70R Number of stopovers
     * 70S Airline staff standby
     * 70T Bereavement
     * 70U Family plan infant discount
     * 70V Group own use
     * 70W Military dependents
     * 70X Military group
     * 70Y Non-resident
     * 70Z Disabled person
     * 710 Charter - Child
     * 711 Child discount
     * 712 City/County Government travel
     * 713 Clergy Standby
     * 714 Companion Fare
     * 715 Companion Fare - Prime
     * 716 Companion Partner
     * 717 Companion with age requirement
     * 718 County Government employee
     * 719 Coupon
     * 71A Blind passenger
     * 71B Baggage
     * 71C Adult contract fare
     * 71D Airline employee buddy standby fare
     * 71E Clergy discount
     * 71F Commuter fare
     * 71G Convention fare
     * 71H Coupon discounted fare
     * 71I Child standby
     * 71J Emigrant fare
     * 71K Government inter state fare
     * 71L Group school party
     * 71M Inclusive tour child (group)
     * 71N Inclusive tour adult (group)
     * 71O Incentive certificate fare
     * 71P Internet fare
     * 71Q Journalist of EUR parliament
     * 71R Labor, adult
     * 71S Military/DOD not based in the USA
     * 71T Passenger occupying two seats
     * 71U Patients traveling for medical treatment
     * 71V Pilgrim fare
     * 71W Pilgrim fare (Saudi Arabia)
     * 71X Student standby
     * 71Y Senior citizen with age requirement
     * 71Z University employee
     * 720 Department of Defense
     * 721 Diplomat
     * 722 Disabled, Unable to work (Finland)
     * 723 Discover the Country (Argentina)
     * 724 Economy Discount
     * 725 Eighty percent disabled persons
     * 726 Family Plan
     * 727 Family Plan Children Discount
     * 728 Family Plan (France)
     * 729 Family member - 1st accompanying
     * 72A Visit USA adult
     * 730 Family member - 2nd accompanying
     * 731 Females Traveling Alone in France
     * 732 Foreign Worker discount
     * 733 Foreign Worker Infant discount
     * 734 Foreign Worker Children discount
     * 735 Frequent traveler
     * 736 Frequent traveler - adult
     * 737 Frequent traveler - child
     * 738 Government
     * 739 Government and Military Category Z
     * 740 Government Child
     * 741 Government Contract
     * 742 Government Dependent
     * 743 Government Exchange
     * 744 Government Infant
     * 745 Government order (Germany)
     * 746 Government State fares
     * 747 Government Transportation ordered
     * 748 Group Child
     * 749 Group Infant
     * 750 Group - undefined
     * 751 Group visit another country adult
     * 752 Group visit USA
     * 753 Head of family
     * 754 IATA = Air/Surface
     * 755 Inclusive Tour Child (Individual)
     * 756 Inclusive Tour Infant
     * 757 Inclusive Tour undefined
     * 758 Independent tour
     * 759 Individual early retirement (Finland)
     * 760 Individual inclusive tour
     * 761 Individual Ships Crew
     * 762 Indonesian War Veteran Discount Fare
     * 763 Indonesian Parliament Discount Fare
     * 764 Industry
     * 765 Infant discount
     * 766 Infant without seat
     * 767 Infant with seat
     * 768 Job Corp trainee
     * 769 Military charter
     * 770 Military child
     * 771 Military confirmed
     * 772 Military dependents stationed inside USA
     * 773 Military dependents stationed outside USA
     * 774 Military /DOD based in USA
     * 775 Military family
     * 776 Military inclusive tour
     * 777 Military infant
     * 778 Military Job Corps
     * 779 Military parents/parents in-laws
     * 780 Military personnel based in USA
     * 781 Military personnel based out USA
     * 782 Military recruit
     * 783 Military reserve
     * 784 Military reserve on active duty
     * 785 Military retired
     * 786 Military retired dependent
     * 787 Military spouse
     * 788 Military standby
     * 789 Mini Fare (Argentina)
     * 790 Missionary
     * 791 Missionary Spouse
     * 792 NATO/SHAPE personnel
     * 793 Negative Band Intersectors (Argentina)
     * 794 Other accompanying family member
     * 795 Press
     * 796 Pseudo resident
     * 797 Refugee
     * 798 Resident
     * 799 Resident Abonnement
     * 800 Resident Adult
     * 801 Resident Child
     * 802 Resident family plan child
     * 803 Resident family plan head family
     * 804 Resident family plan infant
     * 805 Resident family plan youth
     * 806 Resident family plan 2nd adult
     * 807 Resident government
     * 808 Resident group
     * 809 Resident infant
     * 810 Resident Senior Citizens
     * 811 Resident Student
     * 812 Resident youth
     * 813 Retiree (wholly domestic Argentina
     * 814 Seaman
     * 815 Seaman fares
     * 816 Seaman Government Order
     * 817 Second Passenger
     * 818 Senior citizen confirmed
     * 819 Senior citizen discount
     * 820 Senior citizen standby
     * 821 Special
     * 822 Special Interior Fare (Argentina)
     * 823 Spouse Fares
     * 824 Standby
     * 825 State government employees
     * 826 Student discount
     * 827 Student Excellence Discount Fare (Indonesia)
     * 828 Student Government Order
     * 829 Swiss Journalist
     * 830 Teacher's fares
     * 831 Teacher (wholly domestic)
     * 832 Time-saver Fares
     * 833 Tour Guide (conductor)
     * 834 Traveling with cello
     * 835 Unaccompanied child
     * 836 Undefined
     * 837 Visit USA child
     * 838 War Veteran, Unable to work
     * 839 Youth confirmed
     * 840 Youth Discount
     * 841 Youth standby
     * 842 Youth student
     * 843 Labor, child
     * 844 Labor, infant
     * 845 80 percent disabled person (Finland)
     * 846 Second passenger (e.g.: companion)
     * 847 Disabled, unable to work (Finland)
     * 848 Government discount (Germany)
     * 849 Individual early retirement (Finland)
     * 850 Special interior fare (Argentina)
     * 851 Border area fare (Argentina)
     * 852 Intra country restricted fares (Argentina)
     * A Adult
     * BS Block space
     * C Child
     * CP Corporate
     * F Female
     * G Group
     * I Individual
     * IF infant female
     * IM infant male
     * IN Infant
     * IZ Individual within a group
     * L Airport lounge member
     * M Male
     * ML Number of meals served
     * MX Maximum number of flights desired
     * N Military
     * NC Number of columns
     * NL Number of lines
     * PX Number of seats occupied by passengers on board
     * S Same surname
     * SP Standby positive
     * SS Standby space available
     * T Frequent traveler
     * TA Total seats available to assign
     * TC Total cabin class/compartment capacity
     * TCA Total cabin/compartment seats with acknowledgment pending for seats
     * TD Number of ticket/document numbers
     * TF Total number of flight segments
     * TS Total seats sold
     * TU Total seats unassigned
     * TUA Total unassigned seats with acknowledgment pending for seats
     * UM Unaccompanied Minor
     *
     * @var string
     */
    public $qualifier;

    /**
     * @var float
     */
    public $rate;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $fareCategory;
}
