<?php

namespace Amadeus\Client\Struct\Travel;

/**
 * Pax
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class Pax
{
    /**
     * @var string
     */
    public $PaxID;

    /**
     * @var string
     */
    public $PTC;

    /**
     * @var string
     */
    public $Birthdate;

    /**
     * @var string
     */
    public $GenderCode;

    /**
     * @var Individual
     */
    public $Individual;

    /**
     * @var ContactInfo
     */
    public $ContactInfo;

    /**
     * @param string $paxId
     * @param string $ptc
     * @param string $birthdate
     * @param string $genderCode
     * @param Individual|null $individual
     * @param ContactInfo|null $contactInfo
     */
    public function __construct($paxId, $ptc, $birthdate, $genderCode, $individual, $contactInfo)
    {
        $this->PaxID = $paxId;
        $this->PTC = $ptc;
        $this->Birthdate = $birthdate;
        $this->GenderCode = $genderCode;
        $this->Individual = $individual;
        $this->ContactInfo = $contactInfo;
    }
}
