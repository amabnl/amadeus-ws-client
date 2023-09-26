<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * Phone
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Phone
{
    /**
     * @var string
     */
    public $PhoneNumber;

    /**
     * @param string $phoneNumber
     */
    public function __construct($phoneNumber)
    {
        $this->PhoneNumber = $phoneNumber;
    }
}
