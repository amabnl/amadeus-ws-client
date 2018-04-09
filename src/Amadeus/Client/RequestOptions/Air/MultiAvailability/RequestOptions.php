<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\RequestOptions\Air\MultiAvailability;

use Amadeus\Client\LoadParamsFromArray;

/**
 * RequestOptions
 *
 * @package Amadeus\Client\RequestOptions\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RequestOptions extends LoadParamsFromArray
{
    const REQ_TYPE_SPECIFIC_FLIGHT = "SF";
    const REQ_TYPE_BY_ARRIVAL_TIME = "TA";
    const REQ_TYPE_BY_DEPARTURE_TIME = "TD";
    const REQ_TYPE_BY_ELAPSED_TIME = "TE";
    const REQ_TYPE_GROUP_AVAILABILITY = "TG";
    const REQ_TYPE_NEUTRAL_ORDER = "TN";
    const REQ_TYPE_NEGOTIATED_SPACE = "TT";

    const CABIN_FIRST = 1;
    const CABIN_BUSINESS = 2;
    const CABIN_ECONOMY_PREMIUM_MAIN = 3;
    const CABIN_ECONOMY_PREMIUM = 5;
    const CABIN_ECONOMY_MAIN = 4;

    const OPTION_TYPE_AWARD_CALENDAR_AVAILABILITY = "ACA";
    const OPTION_TYPE_BIASED = "BIA";
    const OPTION_TYPE_CHARTER_OPTION = "CHA";
    const OPTION_TYPE_DAY_OF_OPERATION = "DAY";
    const OPTION_TYPE_DIRECT_ACCESS = "DIR";
    const OPTION_TYPE_FORWARD_BACKWARD_PENDULUM = "FBP";
    const OPTION_TYPE_FREQUENT_FLYER = "FFL";
    const OPTION_TYPE_FLIGHT_OPTION = "FLO";
    const OPTION_TYPE_FORWARD_SEARCH = "FSE";
    const OPTION_TYPE_HORIZONTAL_GEOMETRY = "HOR";
    const OPTION_TYPE_MULTI_MODAL = "MOD";
    const OPTION_TYPE_MAX_NR_OF_FLIGHT_PER_DAY = "NBI";
    const OPTION_TYPE_MAX_NR_OF_JOURNEY_PER_DAY = "NBJ";
    const OPTION_TYPE_REDEMPTION_GROUP = "RED";
    const OPTION_TYPE_7_DAY_SEARCH = "SEV";
    const OPTION_TYPE_TIME = "TIM";
    const OPTION_TYPE_TIME_WINDOW = "TIW";
    const OPTION_TYPE_VERTICAL_GEOMETRY = "VER";
    const OPTION_TYPE_ZONE = "ZON";


    /**
     * Departure date
     *
     * @var \DateTime
     */
    public $departureDate;

    /**
     * Arrival date
     *
     * @var \DateTime
     */
    public $arrivalDate;

    /**
     * Departure location
     *
     * @var string
     */
    public $from;

    /**
     * Destination location
     *
     * @var string
     */
    public $to;

    /**
     * Cabin code to select
     *
     * self::CABIN_*
     *
     * @var int
     */
    public $cabinCode;

    /**
     * List of booking classes to include in request
     * @var string[]
     */
    public $bookingClasses = [];

    /**
     * Airlines included in request
     *
     * @var string[]
     */
    public $includedAirlines = [];

    /**
     * Airlines excluded from request
     *
     * @var string[]
     */
    public $excludedAirlines = [];

    /**
     * Operational airline
     *
     * @var string
     */
    public $operationalAirline;

    /**
     * Which connections to choose
     *
     * @var string[]
     */
    public $includedConnections = [];

    /**
     * Which connections to exclude
     *
     * @var string[]
     */
    public $excludedConnections = [];

    /**
     * Flight Number
     *
     * @var string
     */
    public $flightNumber;

    /**
     * How many seats to request
     *
     * @var int
     */
    public $nrOfSeats;

    /**
     * Which type of availability request?
     *
     * self::REQ_TYPE_*
     *
     * @var string
     */
    public $requestType;

    /**
     * Availability options
     *
     * Air_MultiAvailability/requestSection/availabilityOptions/optionInfo
     *
     * Keys are option types (self::OPTION_TYPE_*),
     * values are option arguments.
     *
     * Possible arguments:
     * 12 Time on 12 h
     * 24 Time on 24 h
     * EST Enriched staff
     * EU Europe
     * FR Friday
     * GR Group
     * MA show air only
     * MF show ferry only
     * MM show air and non air services
     * MN show non-air-services only
     * MO Monday
     * MR show rail services only
     * OB Request charter and scheduled flights
     * OC Request connections only
     * OD Request direct flights only
     * OF Request charter flights only or geometry of display
     * OL Request On-line connections only
     * OM Request meal flights only
     * ON Request non-stop flights only or geometry of display
     * OS Request scheduled flights only
     * RE Redemption
     * SA Saturday
     * ST Staff
     * SU Sunday
     * TH Thursday
     * TU Tuesday
     * US USA
     * WE Wednesday
     *
     * @var string[]
     */
    public $availabilityOptions = [];
}
