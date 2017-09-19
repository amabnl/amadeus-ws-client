<?php
/**
 * Amadeus
 *
 * Copyright 2015 Amadeus Benelux NV
 */

namespace Amadeus\Client\Struct\Pnr\DisplayHistory;

/**
 * FreeTextDetails
 *
 * @package Amadeus\Client\Struct\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FreeTextDetails
{
    const QUALIFIER_LITERAL_TEXT = 3;

    const SOURCE_AMADEUS = "A";
    const SOURCE_MANUAL = "M";

    const ENCODING_ASCII_8BIT = 2;

    /**
     * @var int
     */
    public $textSubjectQualifier = self::QUALIFIER_LITERAL_TEXT;

    /**
     * @var string
     */
    public $source = self::SOURCE_AMADEUS;

    /**
     * @var int
     */
    public $encoding = self::ENCODING_ASCII_8BIT;
}
