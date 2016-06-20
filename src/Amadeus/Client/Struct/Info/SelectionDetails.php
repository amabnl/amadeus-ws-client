<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Info;

/**
 * SelectionDetails
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class SelectionDetails
{
    const OPT_SEARCH_ALGORITHM = "ALG";

    const OPT_LOCATION_TYPE = "LTY";


    const OPTINF_AIRPORT = "A";

    const OPTINF_ALL_LOCATIONS = "ALL";

    const OPTINF_BUS_STATION = "B";

    const OPTINF_CITY = "C";

    const OPTINF_SEARCH_EXACT_MATCH = "EXT";

    const OPTINF_GROUND_TRANSPORT = "G";

    const OPTINF_HELIPORT = "H";

    const OPTINF_OFFLINE_POINT = "O";

    const OPTINF_SEARCH_PHONETIC = "PHO";

    const OPTINF_RAILWAY_STATION = "R";

    const OPTINF_ASSOCIATED_LOCATION = "S";


    /**
     * self::OPT_*
     *
     * @var string
     */
    public $option;

    /**
     * self::OPTINF_*
     *
     * @var string
     */
    public $optionInformation;

    /**
     * SelectionDetails constructor.
     *
     * @param string $optionInfo self::OPTINF_*
     * @param string $option self::OPT_*
     */
    public function __construct($optionInfo, $option)
    {
        $this->optionInformation = $optionInfo;
        $this->option = $option;
    }
}
