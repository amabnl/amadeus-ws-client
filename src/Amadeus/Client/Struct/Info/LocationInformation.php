<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Info;

/**
 * LocationInformation
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class LocationInformation
{
    const TYPE_LOCATION = "L";

    const TYPE_ALL = "ALL";

    /**
     * A Airport
     * B Bus station
     * C City
     * G Ground transport
     * H Heliport
     * L Location
     * O Offpoint
     * R Railway station
     * S Associated location
     *
     * From the Amadeus Web Services docs:
     *     The category of the input location is ignored,
     *     i.e. the search is performed among all types of location: city, airport, etc.
     *     However, the category of the returned locations may be specified (option 'LTY').
     *
     * @var string
     */
    public $locationType;

    /**
     * @var LocationDescription
     */
    public $locationDescription;

    /**
     * LocationInformation constructor.
     *
     * @param string $type
     * @param string|null $code
     * @param string|null $name
     */
    public function __construct($type = self::TYPE_LOCATION, $code = null, $name = null)
    {
        $this->locationType = $type;
        $this->locationDescription = new LocationDescription($code, $name);
    }
}
