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

/**
 * CreditCardDetailedData
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class CreditCardDetailedData
{
    /**
     * @var AuthorisationSupplementaryData
     */
    public $authorisationSupplementaryData;

    /**
     * @var ApprovalDetails
     */
    public $approvalDetails;

    /**
     * @var LocalDateTime[]
     */
    public $localDateTime = [];

    /**
     * @var AuthorisationInformation
     */
    public $authorisationInformation;

    /**
     * @var BrowserData
     */
    public $browserData;

    /**
     * @var TdsInformation
     */
    public $tdsInformation;

    /**
     * @var CardSupplementaryData[]
     */
    public $cardSupplementaryData = [];

    /**
     * @var TransactionStatus[]
     */
    public $transactionStatus = [];

    /**
     * CreditCardDetailedData constructor.
     *
     * @param string $approvalCode
     * @param string $approvalSource
     */
    public function __construct($approvalCode, $approvalSource)
    {
        $this->approvalDetails = new ApprovalDetails($approvalCode, $approvalSource);
    }
}
