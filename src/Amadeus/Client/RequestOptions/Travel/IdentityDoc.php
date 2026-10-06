<?php

namespace Amadeus\Client\RequestOptions\Travel;

use Amadeus\Client\LoadParamsFromArray;

/**
 * IdentityDoc - request options
 *
 * Passenger identity document (IdentityDocType). $identityDocID and
 * $identityDocTypeCode are mandatory; the remaining fields are optional and
 * emitted only when supplied.
 *
 * @package Amadeus\Client\RequestOptions\Travel
 */
class IdentityDoc extends LoadParamsFromArray
{
    /**
     * Unique identifier of the document (e.g. passport number).
     *
     * @var string
     */
    public $identityDocID;

    /**
     * Identity document type code (PADIS 1002). Example: PT for passport.
     *
     * @var string
     */
    public $identityDocTypeCode;

    /**
     * ISO country code of the authority that issued the document - optional.
     *
     * @var string|null
     */
    public $issuingCountryCode;

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
     * Given name(s) as printed on the document - optional.
     *
     * @var string|string[]|null
     */
    public $givenName;

    /**
     * Issue date (Y-m-d) - optional.
     *
     * @var \DateTime|string|null
     */
    public $issueDate;

    /**
     * Expiry date (Y-m-d) - optional.
     *
     * @var \DateTime|string|null
     */
    public $expiryDate;

    /**
     * Date of birth (Y-m-d) - optional.
     *
     * @var \DateTime|string|null
     */
    public $birthdate;

    /**
     * Gender code (GenderCode) - optional. Example: M, F.
     *
     * @var string|null
     */
    public $genderCode;
}
