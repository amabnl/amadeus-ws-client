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
use Amadeus\Client\Struct\Air\RetrieveSeatMap\ConversionRate;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\FrequentTravelerInfo;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\ProcessIndicators;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\ProductInformation;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\ResControlInfo;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\SeatRequestParameters;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\StatusInformation;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\Traveller;
use Amadeus\Client\Struct\Air\RetrieveSeatMap\TravelProductIdent;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * RetrieveSeatMap
 *
 * @package Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RetrieveSeatMap extends BaseWsMessage
{
    /**
     * @var RetrieveSeatMap\TravelProductIdent
     */
    public $travelProductIdent;

    /**
     * @var RetrieveSeatMap\SeatRequestParameters
     */
    public $seatRequestParameters;

    /**
     * @var RetrieveSeatMap\ProductInformation
     */
    public $productInformation;

    /**
     * @var RetrieveSeatMap\FrequentTravelerInfo
     */
    public $frequentTravelerInfo;

    /**
     * @var RetrieveSeatMap\ResControlInfo
     */
    public $resControlInfo;

    public $equipmentInformation;

    public $additionalInfo;

    /**
     * @var RetrieveSeatMap\ConversionRate
     */
    public $conversionRate;

    /**
     * @var RetrieveSeatMap\Traveller[]
     */
    public $traveler = [];

    public $suitablePassenger;

    /**
     * @var ProcessIndicators
     */
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

        $this->loadSeatRequestParameters($options);

        $this->loadConversionRate($options);

        $this->loadRecordLocator($options);

        $this->loadProductInformation($options);

        $this->loadPassengers($options);

        if ($options->mostRestrictive) {
            $this->processIndicators = new ProcessIndicators(StatusInformation::ACTION_MOST_RESTRICTIVE);
        }
    }

    /**
     * @param AirRetrieveSeatMapOptions $options
     */
    protected function loadSeatRequestParameters($options)
    {
        if (!empty($options->cabinCode) || $options->requestPrices === true) {
            $this->seatRequestParameters = new SeatRequestParameters(
                $options->cabinCode,
                $options->requestPrices
            );
        }
    }

    /**
     * @param AirRetrieveSeatMapOptions $options
     */
    protected function loadRecordLocator($options)
    {
        if (!empty($options->recordLocator)) {
            $this->resControlInfo = new ResControlInfo(
                $options->recordLocator,
                $options->company,
                $options->date
            );
        }
    }

    /**
     * @param AirRetrieveSeatMapOptions $options
     */
    protected function loadProductInformation($options)
    {
        if (!empty($options->nrOfPassengers) || !empty($options->bookingStatus)) {
            $this->productInformation = new ProductInformation(
                $options->nrOfPassengers,
                $options->bookingStatus
            );
        }
    }

    /**
     * @param AirRetrieveSeatMapOptions $options
     */
    protected function loadConversionRate($options)
    {
        if (!empty($options->currency)) {
            $this->conversionRate = new ConversionRate($options->currency);
        }
    }

    /**
     * @param AirRetrieveSeatMapOptions $options
     */
    protected function loadPassengers($options)
    {
        foreach ($options->travellers as $traveller) {
            $this->traveler[] = new Traveller($traveller);
        }
    }
}
