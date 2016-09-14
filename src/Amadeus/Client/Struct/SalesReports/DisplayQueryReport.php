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

namespace Amadeus\Client\Struct\SalesReports;

use Amadeus\Client\RequestOptions\SalesReportsDisplayQueryReportOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\RequestOption;

/**
 * DisplayQueryReport
 *
 * @package Amadeus\Client\Struct\SalesReports
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class DisplayQueryReport extends BaseWsMessage
{
    /**
     * @var DisplayQueryReport\ActionDetails
     */
    public $actionDetails;

    /**
     * @var DisplayQueryReport\AgentUserDetails
     */
    public $agentUserDetails;

    /**
     * @var DisplayQueryReport\DateDetails
     */
    public $dateDetails;

    /**
     * @var DisplayQueryReport\CurrencyInfo
     */
    public $currencyInfo;

    /**
     * @var DisplayQueryReport\AgencyDetails
     */
    public $agencyDetails;

    /**
     * @var DisplayQueryReport\SalesPeriodDetails
     */
    public $salesPeriodDetails;

    /**
     * @var DisplayQueryReport\TransactionData[]
     */
    public $transactionData = [];

    /**
     * @var DisplayQueryReport\FormOfPaymentDetails
     */
    public $formOfPaymentDetails;

    /**
     * @var DisplayQueryReport\ValidatingCarrierDetails
     */
    public $validatingCarrierDetails;

    /**
     * @var DisplayQueryReport\RequestOption
     */
    public $requestOption;

    /**
     * @var DisplayQueryReport\SalesIndicator
     */
    public $salesIndicator;

    /**
     * @var DisplayQueryReport\FromSequenceDocumentNumber
     */
    public $fromSequenceDocumentNumber;

    /**
     * @var DisplayQueryReport\AttributeInfo
     */
    public $attributeInfo;

    /**
     * DisplayQueryReport constructor.
     *
     * @param SalesReportsDisplayQueryReportOptions $options
     */
    public function __construct(SalesReportsDisplayQueryReportOptions $options)
    {
        $this->loadRequestOptions($options->requestOptions);

        $this->loadAgencySource($options->agencySourceType, $options->agencyIataNumber, $options->agencyOfficeId);

        $this->loadAgent($options->agentCode);

        $this->loadTransaction(
            $options->transactionCode,
            $options->transactionType,
            $options->transactionIssueIndicator
        );

        $this->loadValidatingCarrier($options->validatingCarrier);

        $this->loadDateRange($options->startDate, $options->endDate);

        $this->loadDate($options->specificDateType, $options->specificDate);

        $this->loadCurrency($options->currencyType, $options->currency);

        $this->loadFormOfPayment($options->fopType, $options->fopVendor);

        $this->loadSalesIndicator($options->salesIndicator);

        $this->loadScrolling($options->scrollingCount, $options->scrollingFromItem);
    }

    /**
     * @param array|null $requestOptions
     */
    protected function loadRequestOptions($requestOptions)
    {
        if (!empty($requestOptions)) {
            $this->requestOption = new RequestOption($requestOptions);
        }
    }

    /**
     * @param string|null $agencySourceType
     * @param string|null $iataNumber
     * @param string|null $officeId
     */
    protected function loadAgencySource($agencySourceType, $iataNumber, $officeId)
    {
    }

    /**
     * @param string|null $agentCode
     */
    protected function loadAgent($agentCode)
    {
    }

    /**
     * @param string|null $type
     * @param string|null $code
     * @param string|null $issueIndicator
     */
    protected function loadTransaction($type, $code, $issueIndicator)
    {
    }

    /**
     * @param string|null $validatingCarrier
     */
    protected function loadValidatingCarrier($validatingCarrier)
    {
    }

    /**
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     */
    protected function loadDateRange($startDate, $endDate)
    {
    }

    /**
     * @param string|null $type
     * @param \DateTime|null $date
     */
    protected function loadDate($type, $date)
    {
    }

    /**
     * @param string $type
     * @param string $currency
     */
    protected function loadCurrency($type, $currency)
    {
    }

    /**
     * @param string $type
     * @param string $vendor
     */
    protected function loadFormOfPayment($type, $vendor)
    {
    }

    /**
     * @param string $indicator
     */
    protected function loadSalesIndicator($indicator)
    {
    }

    /**
     * @param int $count
     * @param string $fromItem
     */
    protected function loadScrolling($count, $fromItem)
    {
    }
}
