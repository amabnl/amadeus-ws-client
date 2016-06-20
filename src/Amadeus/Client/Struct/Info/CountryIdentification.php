<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Info;

/**
 * CountryIdentification
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class CountryIdentification
{
    /**
     * 2-char ISO 3166 Country code
     *
     * @var string
     */
    public $countryCode;

    /**
     * Identification of the name of sub-entities ( state, province)
     * defined by appropriate governmental agencies
     *
     * @var string
     */
    public $stateCode;

    /**
     * CountryIdentification constructor.
     *
     * @param string $countryCode
     * @param string|null $stateCode
     */
    public function __construct($countryCode, $stateCode = null)
    {
        $this->countryCode = $countryCode;
        $this->stateCode = $stateCode;
    }
}
