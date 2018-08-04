<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * AvailabilityDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AvailabilityDetails
{
    /**
     * @var string
     */
    public $departureDate;

    /**
     * @var string
     */
    public $departureTime;

    /**
     * @var string
     */
    public $arrivalDate;

    /**
     * @var string
     */
    public $arrivalTime;

    /**
     * AvailabilityDetails constructor.
     *
     * @param \DateTime $departureDate
     * @param \DateTime|null $arrivalDate
     */
    public function __construct($departureDate, $arrivalDate = null)
    {
        if ($departureDate instanceof \DateTime) {
            $depArr = $this->loadDateInfo($departureDate);
            $this->departureDate = $depArr['date'];
            $this->departureTime = $depArr['time'];
        }

        if ($arrivalDate instanceof \DateTime) {
            $arrArr = $this->loadDateInfo($arrivalDate);
            $this->arrivalDate = $arrArr['date'];
            $this->arrivalTime = $arrArr['time'];
        }
    }

    /**
     * @param \DateTime $date
     * @return array ['date' => '200316', 'time'=>'1415']
     */
    protected function loadDateInfo(\DateTime $date)
    {
        $dateInfo = [
            'date' => null,
            'time' => null
        ];

        $dateInfo['date'] = $date->format('dmy');

        $time = $date->format('Hi');
        if ($time !== '0000') {
            $dateInfo['time'] = $time;
        }

        return $dateInfo;
    }
}
