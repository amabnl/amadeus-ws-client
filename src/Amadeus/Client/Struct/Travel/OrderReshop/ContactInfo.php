<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * ContactInfo
 *
 * Passenger contact channel (ContactInfoType). Phone and EmailAddress are
 * emitted only when supplied.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class ContactInfo
{
    /**
     * @var Phone|null
     */
    public $Phone;

    /**
     * @var EmailAddress|null
     */
    public $EmailAddress;

    /**
     * @param string|null $phoneNumber
     * @param string|null $phoneLabel
     * @param string|null $email
     * @param string|null $emailLabel
     */
    public function __construct($phoneNumber, $phoneLabel, $email, $emailLabel)
    {
        if ($phoneNumber !== null) {
            $this->Phone = new Phone($phoneNumber, $phoneLabel);
        }

        if ($email !== null) {
            $this->EmailAddress = new EmailAddress($email, $emailLabel);
        }
    }
}
