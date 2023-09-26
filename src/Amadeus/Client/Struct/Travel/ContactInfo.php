<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * ContactInfo
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
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
     * @var bool|null
     */
    public $ContactRefusedInd;

    /**
     * @param Phone $phone
     * @param EmailAddress $emailAddress
     * @param bool $contactRefusedInd
     */
    public function __construct($phone, $emailAddress, $contactRefusedInd)
    {
        $this->Phone = $phone;
        $this->EmailAddress = $emailAddress;
        $this->ContactRefusedInd = $contactRefusedInd;
    }
}
