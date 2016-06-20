<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Info;

/**
 * LanguageDetails
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class LanguageDetails
{
    /**
     * @var string
     */
    public $languageCode;

    /**
     * LanguageDetails constructor.
     *
     * @param string $languageCode
     */
    public function __construct($languageCode)
    {
        $this->languageCode = $languageCode;
    }
}
