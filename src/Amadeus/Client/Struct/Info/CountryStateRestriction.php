<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Info;

/**
 * CountryStateRestriction
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class CountryStateRestriction
{
    /**
     * @var CountryIdentification
     */
    public $countryIdentification;

    /**
     * CountryStateRestriction constructor.
     *
     * @param string $countryCode
     * @param string|null $stateCode
     */
    public function __construct($countryCode, $stateCode = null)
    {
        $this->countryIdentification = new CountryIdentification($countryCode, $stateCode);
    }
}
