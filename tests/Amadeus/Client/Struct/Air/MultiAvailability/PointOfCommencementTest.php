<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Test\Amadeus\Client\Struct\Air\MultiAvailability;
use Amadeus\Client\Struct\Air\MultiAvailability\PointOfCommencement;
use Test\Amadeus\BaseTestCase;

/**
 * PointOfCommencementTest
 *
 * @package Test\Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PointOfCommencementTest extends BaseTestCase
{
    public function testCanMakeWithTime()
    {
        $obj = new PointOfCommencement(
            'LON',
            \DateTime::createFromFormat('Ymd-His', '20170320-121500', new \DateTimeZone('UTC'))
        );
        $this->assertEquals('LON', $obj->location);
        $this->assertEquals('200317', $obj->date);
        $this->assertEquals('1215', $obj->time);
    }
}
