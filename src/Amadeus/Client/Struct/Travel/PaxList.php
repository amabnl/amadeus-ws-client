<?php

namespace Amadeus\Client\Struct\Travel;

use Amadeus\Client\RequestOptions\Travel as TravelRequest;

/**
 * PaxList
 *
 * @package Amadeus\Client\Struct\Travel
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class PaxList
{
    /**
     * @var Pax[]
     */
    public $Pax;

    /**
     * @param TravelRequest\PaxList $requestPaxList
     */
    public function __construct(TravelRequest\PaxList $requestPaxList)
    {
        $this->loadPaxes($requestPaxList);
    }

    private function loadPaxes(TravelRequest\PaxList $requestPaxList)
    {
        $this->Pax = array_map(static function (TravelRequest\Pax $requestPax) {
            $individual = null;

            if ($requestPax->firstName && $requestPax->lastName) {
                $individual = new Individual(
                    $requestPax->firstName,
                    $requestPax->lastName,
                    $requestPax->genderCode
                );
            }

            $contactInfo = null;

            if (($phoneNumber = $requestPax->phoneNumber) && ($email = $requestPax->email)) {
                $contactInfo = new ContactInfo(
                    new Phone($phoneNumber),
                    new EmailAddress('HOME', $email),
                    $requestPax->passengerContactRefused,
                );
            }

            return new Pax(
                $requestPax->paxId,
                $requestPax->ptc,
                ($dob = $requestPax->dob) ? $dob->format('Y-m-d') : null,
                $requestPax->genderCode,
                $individual,
                $contactInfo,
                $requestPax->paxRefId
            );
        }, $requestPaxList->pax);
    }
}
