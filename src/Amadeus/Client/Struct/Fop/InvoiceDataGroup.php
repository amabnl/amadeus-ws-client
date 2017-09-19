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

use Amadeus\Client\RequestOptions\Fop\InvoiceInfo;

/**
 * InvoiceDataGroup
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InvoiceDataGroup
{
    /**
     * @var InvoiceInformation
     */
    public $invoiceInformation;

    /**
     * @var Routing
     */
    public $routing;

    /**
     * @var mixed[]
     */
    public $iruQualifier = [];

    /**
     * @var mixed[]
     */
    public $fopInformationGroup = [];

    /**
     * @var mixed[]
     */
    public $accountSupplementaryData = [];

    /**
     * @var mixed
     */
    public $bookingReference;

    /**
     * @var mixed[]
     */
    public $parentTicketGroup = [];

    /**
     * @var mixed[]
     */
    public $ruleList = [];

    /**
     * InvoiceDataGroup constructor.
     *
     * @param InvoiceInfo $invoiceInfo
     */
    public function __construct($invoiceInfo)
    {
        $this->invoiceInformation = new InvoiceInformation(
            $invoiceInfo->formOfPayment,
            $invoiceInfo->customerAccount,
            $invoiceInfo->membershipStatus,
            $invoiceInfo->merchantCode
        );

        $this->routing = new Routing($invoiceInfo->routingStation);
    }
}
