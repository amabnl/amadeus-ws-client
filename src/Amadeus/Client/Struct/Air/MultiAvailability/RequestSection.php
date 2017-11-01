<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

use Amadeus\Client\RequestOptions\Air\MultiAvailability\RequestOptions;

/**
 * RequestSection
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class RequestSection
{
    /**
     * @var AvailabilityProductInfo
     */
    public $availabilityProductInfo;

    /**
     * @var BoardOffDetails[]
     */
    public $boardOffDetails = [];

    /**
     * @var TravelChoiceInfo
     */
    public $travelChoiceInfo;

    /**
     * @var OptionClass
     */
    public $optionClass;

    /**
     * @var ConnectionOption[]
     */
    public $connectionOption = [];

    /**
     * @var NumberOfSeatsInfo
     */
    public $numberOfSeatsInfo;

    /**
     * @var AirlineOrFlightOption[]
     */
    public $airlineOrFlightOption = [];

    /**
     * @var CabinOption
     */
    public $cabinOption;

    /**
     * @var NegoSpaceDetails
     */
    public $negoSpaceDetails;

    /**
     * @var AvailabilityOptions
     */
    public $availabilityOptions;

    /**
     * @var UmRequest
     */
    public $umRequest;

    /**
     * RequestSection constructor.
     *
     * @param RequestOptions $params
     */
    public function __construct(RequestOptions $params)
    {
        $this->availabilityProductInfo = new AvailabilityProductInfo(
            $params->from,
            $params->to,
            $params->departureDate,
            $params->arrivalDate
        );

        if ($this instanceof RequestSection16) {
            $this->availabilityOptions = new AvailabilityOptions16($params->requestType);
        } else {
            $this->availabilityOptions = new AvailabilityOptions($params->requestType);
        }

        $this->loadCabinAndClass($params->cabinCode, $params->bookingClasses);

        $this->loadAirlines(
            $params->includedAirlines,
            $params->excludedAirlines,
            $params->operationalAirline,
            $params->flightNumber
        );

        $this->loadConnections($params->includedConnections, $params->excludedConnections);

        $this->loadSeats($params->nrOfSeats);

        $this->loadAvailabilityOptions($params->availabilityOptions);
    }

    /**
     * @param string|null $cabinCode
     * @param string[] $bookingClasses
     */
    protected function loadCabinAndClass($cabinCode, $bookingClasses)
    {
        if (!empty($cabinCode)) {
            $this->cabinOption = new CabinOption($cabinCode);
        }

        if (!empty($bookingClasses)) {
            $this->optionClass = new OptionClass($bookingClasses);
        }
    }

    /**
     * @param string[] $included
     * @param string[] $excluded
     * @param string|null $operational
     * @param string|null $flightNumber
     */
    protected function loadAirlines($included, $excluded, $operational, $flightNumber)
    {
        if (!empty($included)) {
            $this->airlineOrFlightOption[] = new AirlineOrFlightOption($included, $flightNumber);
        }

        if (!empty($excluded)) {
            $this->airlineOrFlightOption[] = new AirlineOrFlightOption(
                $excluded,
                null,
                AirlineOrFlightOption::INDICATOR_EXCLUDE
            );
        }

        if (!empty($operational)) {
            $this->airlineOrFlightOption[] = new AirlineOrFlightOption(
                [$operational],
                $flightNumber,
                AirlineOrFlightOption::INDICATOR_OPERATIONAL_CARRIER
            );
        }
    }

    /**
     * @param string[] $included
     * @param string[] $excluded
     */
    protected function loadConnections($included, $excluded)
    {
        if (!empty($included)) {
            $this->connectionOption[] = new ConnectionOption($included);
        }

        if (!empty($excluded)) {
            $this->connectionOption[] = new ConnectionOption($excluded, Connection::INDICATOR_EXCLUDE);
        }
    }

    /**
     * @param int|null $nrOfSeats
     */
    protected function loadSeats($nrOfSeats)
    {
        if (!empty($nrOfSeats)) {
            $this->numberOfSeatsInfo = new NumberOfSeatsInfo($nrOfSeats);
        }
    }

    /**
     * Availability options:
     * Keys are option types (RequestOptions::OPTION_TYPE_*),
     * values are option arguments.
     *
     * @param $availabilityOptions
     */
    protected function loadAvailabilityOptions($availabilityOptions)
    {
        if (!empty($availabilityOptions)) {
            foreach ($availabilityOptions as $type => $argument) {
                $this->availabilityOptions->optionInfo[] = new OptionInfo($type, $argument);
            }
        }
    }
}
