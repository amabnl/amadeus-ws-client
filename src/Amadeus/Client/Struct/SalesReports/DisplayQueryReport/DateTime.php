<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * DateTime
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DateTime
{
    /**
     * @var string
     */
    public $year;

    /**
     * @var string
     */
    public $month;

    /**
     * @var string
     */
    public $day;

    /**
     * DateTime constructor.
     *
     * @param \DateTime|null $date
     */
    public function __construct($date)
    {
        if ($date instanceof \DateTime) {
            $this->year = $date->format('Y');
            $this->month = $date->format('m');
            $this->day = $date->format('d');
        }
    }
}
