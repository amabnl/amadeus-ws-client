<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\IdentityDoc as RequestIdentityDoc;

/**
 * IdentityDoc
 *
 * Passenger identity document (IdentityDocType). IdentityDocID and
 * IdentityDocTypeCode are mandatory; the remaining nodes are emitted only when
 * supplied and follow the XSD element order.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class IdentityDoc
{
    /**
     * @var string
     */
    public $IdentityDocID;

    /**
     * @var string
     */
    public $IdentityDocTypeCode;

    /**
     * @var string|null
     */
    public $IssuingCountryCode;

    /**
     * @var string|null
     */
    public $CitizenshipCountryCode;

    /**
     * @var string|null
     */
    public $ResidenceCountryCode;

    /**
     * @var string|string[]|null
     */
    public $GivenName;

    /**
     * @var string|null
     */
    public $IssueDate;

    /**
     * @var string|null
     */
    public $ExpiryDate;

    /**
     * @var string|null
     */
    public $Birthdate;

    /**
     * @var string|null
     */
    public $GenderCode;

    /**
     * @param RequestIdentityDoc $doc
     */
    public function __construct(RequestIdentityDoc $doc)
    {
        $this->IdentityDocID = $doc->identityDocID;
        $this->IdentityDocTypeCode = $doc->identityDocTypeCode;

        if ($doc->issuingCountryCode !== null) {
            $this->IssuingCountryCode = $doc->issuingCountryCode;
        }

        if ($doc->citizenshipCountryCode !== null) {
            $this->CitizenshipCountryCode = $doc->citizenshipCountryCode;
        }

        if ($doc->residenceCountryCode !== null) {
            $this->ResidenceCountryCode = $doc->residenceCountryCode;
        }

        if ($doc->givenName !== null) {
            $this->GivenName = $doc->givenName;
        }

        if ($doc->issueDate !== null) {
            $this->IssueDate = self::formatDate($doc->issueDate);
        }

        if ($doc->expiryDate !== null) {
            $this->ExpiryDate = self::formatDate($doc->expiryDate);
        }

        if ($doc->birthdate !== null) {
            $this->Birthdate = self::formatDate($doc->birthdate);
        }

        if ($doc->genderCode !== null) {
            $this->GenderCode = $doc->genderCode;
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
