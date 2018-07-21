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
 * MopDetailedData
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MopDetailedData
{
    /**
     * @var FopInformation
     */
    public $fopInformation;

    /**
     * @var FundTransferDetailledData
     */
    public $fundTransferDetailledData;

    /**
     * @var AsyncDetailledDataGroup
     */
    public $asyncDetailledDataGroup;

    /**
     * @var string
     */
    public $dummy;

    /**
     * @var InvoiceDetailedDataGroup
     */
    public $invoiceDetailedDataGroup;

    /**
     * @var CreditCardDetailedData
     */
    public $creditCardDetailedData;

    /**
     * @var WebAccountDetailledData
     */
    public $webAccountDetailledData;

    /**
     * @var VirtualCreditCardStatusGroup
     */
    public $virtualCreditCardStatusGroup;

    /**
     * MopDetailedData constructor.
     *
     * @param string $fopType
     */
    public function __construct($fopType)
    {
        $this->fopInformation = new FopInformation($fopType);
    }
}
