<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * Individual
 *
 * A single human being (IndividualType). All nodes are optional and emitted
 * only when supplied.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class Individual
{
    /**
     * @var string|null
     */
    public $TitleName;

    /**
     * @var string|null
     */
    public $GivenName;

    /**
     * @var string|null
     */
    public $Surname;

    /**
     * @var string|null
     */
    public $GenderCode;

    /**
     * @param string|null $titleName
     * @param string|null $givenName
     * @param string|null $surname
     * @param string|null $genderCode
     */
    public function __construct($titleName, $givenName, $surname, $genderCode)
    {
        if ($titleName !== null) {
            $this->TitleName = $titleName;
        }

        if ($givenName !== null) {
            $this->GivenName = $givenName;
        }

        if ($surname !== null) {
            $this->Surname = $surname;
        }

        if ($genderCode !== null) {
            $this->GenderCode = $genderCode;
        }
    }
}
