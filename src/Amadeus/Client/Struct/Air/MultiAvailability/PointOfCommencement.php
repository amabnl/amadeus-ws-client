<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * PointOfCommencement
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PointOfCommencement
{
    /**
     * @var string
     */
    public $location;

    /**
     * @var string
     */
    public $date;

    /**
     * @var string
     */
    public $time;

    /**
     * PointOfCommencement constructor.
     *
     * @param string $point
     * @param \DateTime|null $date
     */
    public function __construct($point, $date)
    {
        $this->location = $point;

        if ($date instanceof \DateTime) {
            $this->date = $date->format('dmy');

            $time = $date->format('Hi');
            if ($time !== '0000') {
                $this->time = $time;
            }
        }
    }
}
