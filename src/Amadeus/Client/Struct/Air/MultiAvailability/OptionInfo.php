<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * OptionInfo
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OptionInfo
{
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
     * self::OPTION_TYPE_*
     *
     * @var string
     */
    public $type;

    /**
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
     * SA Saterday
     * ST Staff
     * SU Sunday
     * TH Thursday
     * TU Tuesday
     * US USA
     * WE Wednesday
     *
     * @var string[]
     */
    public $arguments = [];


    /**
     * OptionInfo constructor.
     *
     * @param string $type
     * @param string $argument
     */
    public function __construct($type, $argument)
    {
        $this->type = $type;
        $this->arguments[] = $argument;
    }
}
