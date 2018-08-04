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
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\ActionDetails;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\AgencyDetails;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\AgentUserDetails;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\CurrencyInfo;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\DateDetails;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\FormOfPaymentDetails;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\RequestOption;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\SalesIndicator;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\SalesPeriodDetails;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\TransactionData;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\ValidatingCarrierDetails;

/**
 * DisplayQueryReport
 *
 * @package Amadeus\Client\Struct\SalesReports
 * @author Dieter Devlieghere <dermikagh@gmail.com>
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
        if ($this->checkAnyNotEmpty($agencySourceType, $iataNumber, $officeId)) {
            $this->agencyDetails = new AgencyDetails($agencySourceType, $iataNumber, $officeId);
        }
    }

    /**
     * @param string|null $agentCode
     */
    protected function loadAgent($agentCode)
    {
        if (!empty($agentCode)) {
            $this->agentUserDetails = new AgentUserDetails($agentCode);
        }
    }

    /**
     * @param string|null $code
     * @param string|null $type
     * @param string|null $issueIndicator
     */
    protected function loadTransaction($code, $type, $issueIndicator)
    {
        if ($this->checkAnyNotEmpty($type, $code, $issueIndicator)) {
            $this->transactionData[] = new TransactionData($type, $code, $issueIndicator);
        }
    }

    /**
     * @param string|null $validatingCarrier
     */
    protected function loadValidatingCarrier($validatingCarrier)
    {
        if (!empty($validatingCarrier)) {
            $this->validatingCarrierDetails = new ValidatingCarrierDetails($validatingCarrier);
        }
    }

    /**
     * @param \DateTime|null $startDate
     * @param \DateTime|null $endDate
     */
    protected function loadDateRange($startDate, $endDate)
    {
        if ($this->checkAnyNotEmpty($startDate, $endDate)) {
            $this->salesPeriodDetails = new SalesPeriodDetails($startDate, $endDate);
        }
    }

    /**
     * @param string|null $type
     * @param \DateTime|null $date
     */
    protected function loadDate($type, $date)
    {
        if ($this->checkAnyNotEmpty($type, $date)) {
            $this->dateDetails = new DateDetails($type, $date);
        }
    }

    /**
     * @param string $type
     * @param string $currency
     */
    protected function loadCurrency($type, $currency)
    {
        if ($this->checkAnyNotEmpty($type, $currency)) {
            $this->currencyInfo = new CurrencyInfo($type, $currency);
        }
    }

    /**
     * @param string $type
     * @param string $vendor
     */
    protected function loadFormOfPayment($type, $vendor)
    {
        if ($this->checkAnyNotEmpty($type, $vendor)) {
            $this->formOfPaymentDetails = new FormOfPaymentDetails($type, $vendor);
        }
    }

    /**
     * @param string $indicator
     */
    protected function loadSalesIndicator($indicator)
    {
        if (!empty($indicator)) {
            $this->salesIndicator = new SalesIndicator($indicator);
        }
    }

    /**
     * @param int $count
     * @param string $fromItem
     */
    protected function loadScrolling($count, $fromItem)
    {
        if ($this->checkAnyNotEmpty($count, $fromItem)) {
            $this->actionDetails = new ActionDetails($count, $fromItem);
        }
    }
}
