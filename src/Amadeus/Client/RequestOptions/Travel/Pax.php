<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

class Pax extends LoadParamsFromArray
{
    /**
     * @var string
     */
    public $paxId;

    /**
     * @var string
     */
    public $ptc;

    /**
     * @var \DateTime
     */
    public $dob;

    /**
     * @var string
     */
    public $genderCode;

    /**
     * @var string
     */
    public $firstName;

    /**
     * @var string
     */
    public $lastName;

    /**
     * @var string
     */
    public $phoneNumber;

    /**
     * @var string
     */
    public $email;

    /**
     * @var bool
     */
    public $passengerContactRefused;

    /**
     * @var string|null
     */
    public $paxRefId;
}
