<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FlightInfo as RequestFlightInfo;
use Amadeus\Client\RequestOptions\Air\RetrieveSeatMap\FrequentFlyer;
use Amadeus\Client\RequestOptions\AirRetrieveSeatMapOptions;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\FrequentTravelerInfo;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\TravelProductIdent;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * RetrieveSeatMap
 *
 * @package Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class RetrieveSeatMap extends BaseWsMessage
{
    /**
     * @var RetrieveSeatMap\TravelProductIdent
     */
    public $travelProductIdent;

    public $seatRequestParameters;

    public $productInformation;

    /**
     * @var RetrieveSeatMap\FrequentTravelerInfo
     */
    public $frequentTravelerInfo;

    public $resControlInfo;

    public $equipmentInformation;

    public $additionalInfo;

    public $conversionRate;

    public $traveler = [];

    public $suitablePassenger;

    public $processIndicators;



    /**
     * RetrieveSeatMap constructor.
     *
     * @param AirRetrieveSeatMapOptions $options
     */
    public function __construct(AirRetrieveSeatMapOptions $options)
    {
        if ($options->flight instanceof RequestFlightInfo) {
            $this->travelProductIdent = new TravelProductIdent($options->flight);
        }

        if ($options->frequentFlyer instanceof FrequentFlyer) {
            $this->frequentTravelerInfo = new FrequentTravelerInfo($options->frequentFlyer);
        }
    }
}
