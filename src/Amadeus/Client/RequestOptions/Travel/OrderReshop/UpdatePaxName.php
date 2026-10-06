<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * UpdatePaxName - request options
 *
 * Request to the airline whether fees apply to a proposed correction to a
 * passenger's name details (UpdatePaxNameType). Only PaxRefID is mandatory.
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class UpdatePaxName extends LoadParamsFromArray
{
    /**
     * Reference to the passenger ID for whom the name change is requested.
     *
     * @var string
     */
    public $paxRefId;

    /**
     * Title (TitleName) - optional. Example: Mr.
     *
     * @var string|null
     */
    public $titleName;

    /**
     * Given name(s) (GivenName) - optional.
     *
     * @var string|string[]|null
     */
    public $givenName;

    /**
     * Middle name(s) (MiddleName) - optional.
     *
     * @var string|string[]|null
     */
    public $middleName;

    /**
     * Surname (Surname) - optional.
     *
     * @var string|null
     */
    public $surname;

    /**
     * Suffix (SuffixName) - optional. Example: Jr.
     *
     * @var string|null
     */
    public $suffixName;
}
