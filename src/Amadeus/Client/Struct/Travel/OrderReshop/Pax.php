<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\IdentityDoc as RequestIdentityDoc;
use Amadeus\Client\RequestOptions\Travel\OrderReshop\Pax as RequestPax;

/**
 * Pax
 *
 * Passenger details (PaxType). Only PaxID is mandatory; the remaining nodes are
 * only emitted when the corresponding option is provided.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class Pax
{
    /**
     * @var string
     */
    public $PaxID;

    /**
     * @var string|null
     */
    public $PTC;

    /**
     * @var string|null
     */
    public $Birthdate;

    /**
     * @var string|null
     */
    public $CitizenshipCountryCode;

    /**
     * @var string|null
     */
    public $ResidenceCountryCode;

    /**
     * @var ContactInfo|null
     */
    public $ContactInfo;

    /**
     * @var IdentityDoc[]|null
     */
    public $IdentityDoc;

    /**
     * @var Individual|null
     */
    public $Individual;

    /**
     * @param RequestPax $pax
     */
    public function __construct(RequestPax $pax)
    {
        $this->PaxID = $pax->paxId;

        if ($pax->ptc !== null) {
            $this->PTC = $pax->ptc;
        }

        if ($pax->birthdate !== null) {
            $this->Birthdate = self::formatDate($pax->birthdate);
        }

        if ($pax->citizenshipCountryCode !== null) {
            $this->CitizenshipCountryCode = $pax->citizenshipCountryCode;
        }

        if ($pax->residenceCountryCode !== null) {
            $this->ResidenceCountryCode = $pax->residenceCountryCode;
        }

        if ($pax->phoneNumber !== null || $pax->email !== null) {
            $this->ContactInfo = new ContactInfo(
                $pax->phoneNumber,
                $pax->phoneLabel,
                $pax->email,
                $pax->emailLabel
            );
        }

        if (!empty($pax->identityDocs)) {
            $this->IdentityDoc = array_map(
                static function (RequestIdentityDoc $doc) {
                    return new IdentityDoc($doc);
                },
                $pax->identityDocs
            );
        }

        if ($pax->givenName !== null
            || $pax->surname !== null
            || $pax->titleName !== null
            || $pax->genderCode !== null
        ) {
            $this->Individual = new Individual(
                $pax->titleName,
                $pax->givenName,
                $pax->surname,
                $pax->genderCode
            );
        }
    }

    /**
     * @param \DateTime|string $date
     * @return string
     */
    private static function formatDate($date)
    {
        return $date instanceof \DateTimeInterface ? $date->format('Y-m-d') : $date;
    }
}
