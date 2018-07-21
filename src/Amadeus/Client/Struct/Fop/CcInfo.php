<?php
/**
 * amadeus-ws-client
 *
 * Copyright 2015 Amadeus Benelux NV
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package Amadeus
 * @license https://opensource.org/licenses/Apache-2.0 Apache 2.0
 */

namespace Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\Fop\CreditCardInfo;

/**
 * CcInfo
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class CcInfo
{
    /**
     * @var string
     */
    public $vendorCode;

    /**
     * @var string
     */
    public $vendorCodeSubType;

    /**
     * @var string
     */
    public $cardNumber;

    /**
     * @var string
     */
    public $securityId;

    /**
     * @var string
     */
    public $expiryDate;

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var string
     */
    public $endDate;

    /**
     * @var string
     */
    public $ccHolderName;

    /**
     * @var string
     */
    public $issuingBankName;

    /**
     * @var string
     */
    public $cardCountryOfIssuance;

    /**
     * @var int|string
     */
    public $issueNumber;

    /**
     * @var string
     */
    public $issuingBankLongName;

    /**
     * @var string
     */
    public $track1;

    /**
     * @var string
     */
    public $track2;

    /**
     * @var string
     */
    public $track3;

    /**
     * @var string
     */
    public $pinCode;

    /**
     * @var string
     */
    public $rawTrackData;

    /**
     * @var string
     */
    public $tierLevel;

    /**
     * CcInfo constructor.
     *
     * @param CreditCardInfo $options
     */
    public function __construct(CreditCardInfo $options)
    {
        $this->vendorCode = $options->vendorCode;
        $this->cardNumber = $options->cardNumber;
        $this->expiryDate = $options->expiryDate;
        $this->ccHolderName = $options->name;
        $this->securityId = $options->securityId;
        $this->issueNumber = $options->issueNumber;
        $this->startDate = $options->startDate;
    }
}
