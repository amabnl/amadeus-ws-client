<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Info;

/**
 * Language
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Language
{
    /*
     * From the docs:
     * https://webservices.amadeus.com/extranet/viewUserGuide.do?id=16&flavourId=1&menuId=functional
     *
     * The language in which the location is expressed is ignored,
     * i.e. the search is performed and displayed in all languages
     */

    const QUAL_LANGUAGE_TRANSACTIONAL = 8;

    /**
     * @var int
     */
    public $languageCodeQualifier = self::QUAL_LANGUAGE_TRANSACTIONAL;

    /**
     * @var LanguageDetails
     */
    public $languageDetails;

    /**
     * Language constructor.
     *
     * @param string $languageCode
     */
    public function __construct($languageCode)
    {
        $this->languageDetails = new LanguageDetails($languageCode);
    }
}
