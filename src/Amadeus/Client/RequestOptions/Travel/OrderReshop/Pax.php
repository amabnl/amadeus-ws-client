<?php

namespace Amadeus\Client\RequestOptions\Travel\OrderReshop;

use Amadeus\Client\LoadParamsFromArray;
use Amadeus\Client\RequestOptions\Travel\IdentityDoc;

/**
 * Pax - request options
 *
 * Passenger details (PaxType) used within AddOfferItems/Paxs and within
 * UpdatePax (Current / New). Only PaxID is mandatory; the remaining fields are
 * optional and emitted only when supplied.
 *
 * @package Amadeus\Client\RequestOptions\Travel\OrderReshop
 */
class Pax extends LoadParamsFromArray
{
    /**
     * Uniquely identifies the passenger within the message (PaxID).
     *
     * @var string
     */
    public $paxId;

    /**
     * Passenger type code driving pricing (PTC). Example: ADT, CHD.
     *
     * @var string|null
     */
    public $ptc;

    /**
     * Date of birth (Y-m-d) - optional.
     *
     * @var \DateTime|string|null
     */
    public $birthdate;

    /**
     * ISO citizenship country code - optional.
     *
     * @var string|null
     */
    public $citizenshipCountryCode;

    /**
     * ISO residence country code - optional.
     *
     * @var string|null
     */
    public $residenceCountryCode;

    /**
     * Given name (Individual/GivenName) - optional.
     *
     * @var string|null
     */
    public $givenName;

    /**
     * Surname (Individual/Surname) - optional.
     *
     * @var string|null
     */
    public $surname;

    /**
     * Title (Individual/TitleName) - optional. Example: MR.
     *
     * @var string|null
     */
    public $titleName;

    /**
     * Gender code (Individual/GenderCode) - optional. Example: M, F.
     *
     * @var string|null
     */
    public $genderCode;

    /**
     * Contact phone label (ContactInfo/Phone/LabelText) - optional.
     *
     * @var string|null
     */
    public $phoneLabel;

    /**
     * Contact phone number (ContactInfo/Phone/PhoneNumberText) - optional.
     *
     * @var string|null
     */
    public $phoneNumber;

    /**
     * Contact email label (ContactInfo/EmailAddress/LabelText) - optional.
     *
     * @var string|null
     */
    public $emailLabel;

    /**
     * Contact email (ContactInfo/EmailAddress/EmailAddressText) - optional.
     *
     * @var string|null
     */
    public $email;

    /**
     * Identity document(s) - optional.
     *
     * @var IdentityDoc[]|null
     */
    public $identityDocs;
}
