<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * Individual
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Individual
{
    /**
     * @var string
     */
    public $GivenName;

    /**
     * @var string
     */
    public $Surname;

    /**
     * @var string
     */
    public $GenderCode;

    /**
     * @param string $givenName
     * @param string $surname
     * @param string $genderCode
     */
    public function __construct($givenName, $surname, $genderCode)
    {
        $this->GivenName = $givenName;
        $this->Surname = $surname;
        $this->GenderCode = $genderCode;
    }
}
