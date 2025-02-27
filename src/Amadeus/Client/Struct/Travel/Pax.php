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
     * @var string|null
     */
    public $PaxRefID;

    /**
     * @param string $paxId
     * @param string $ptc
     * @param string $birthdate
     * @param string $genderCode
     * @param Individual|null $individual
     * @param ContactInfo|null $contactInfo
     * @param string|null $paxRefID
     */
    public function __construct($paxId, $ptc, $birthdate, $genderCode, $individual, $contactInfo, $paxRefID = null)
    {
        $this->PaxID = $paxId;
        $this->PTC = $ptc;
        $this->Birthdate = $birthdate;
        $this->GenderCode = $genderCode;
        $this->Individual = $individual;
        $this->ContactInfo = $contactInfo;
        $this->PaxRefID = $paxRefID;
    }
}
