<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

use Amadeus\Client\RequestOptions\Travel\OrderReshop\UpdatePaxName as RequestUpdatePaxName;

/**
 * UpdatePaxName
 *
 * Request whether fees apply to a proposed passenger name correction
 * (UpdatePaxNameType). Element order follows the XSD.
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class UpdatePaxName
{
    /**
     * @var string
     */
    public $PaxRefID;

    /**
     * @var string|null
     */
    public $TitleName;

    /**
     * @var string|string[]|null
     */
    public $GivenName;

    /**
     * @var string|string[]|null
     */
    public $MiddleName;

    /**
     * @var string|null
     */
    public $Surname;

    /**
     * @var string|null
     */
    public $SuffixName;

    /**
     * @param RequestUpdatePaxName $updatePaxName
     */
    public function __construct(RequestUpdatePaxName $updatePaxName)
    {
        $this->PaxRefID = $updatePaxName->paxRefId;

        if ($updatePaxName->titleName !== null) {
            $this->TitleName = $updatePaxName->titleName;
        }

        if ($updatePaxName->givenName !== null) {
            $this->GivenName = $updatePaxName->givenName;
        }

        if ($updatePaxName->middleName !== null) {
            $this->MiddleName = $updatePaxName->middleName;
        }

        if ($updatePaxName->surname !== null) {
            $this->Surname = $updatePaxName->surname;
        }

        if ($updatePaxName->suffixName !== null) {
            $this->SuffixName = $updatePaxName->suffixName;
        }
    }
}
