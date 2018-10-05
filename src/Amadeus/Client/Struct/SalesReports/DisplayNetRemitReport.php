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

use Amadeus\Client\RequestOptions\SalesReportsDisplayNetRemitReportOptions;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\SalesIndicator;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\TransactionData;

/**
 * DisplayNetRemitReport
 *
 * @package Amadeus\Client\Struct\SalesReports
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class DisplayNetRemitReport extends DisplayQueryReport
{
    /**
     * @var DisplayQueryReport\TransactionData[]
     */
    public $transactionTypeCodeInfo;

    /**
     * @var DisplayQueryReport\SalesIndicator
     */
    public $documentInfo;

    /**
     * DisplayDailyOrSummarizedReport constructor.
     *
     * @param SalesReportsDisplayNetRemitReportOptions $options
     */
    public function __construct(SalesReportsDisplayNetRemitReportOptions $options)
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

        $this->loadDocumentInfo($options->documentInfo);
    }

    /**
     * @param string|null $code
     * @param string|null $type
     * @param string|null $issueIndicator
     */
    protected function loadTransaction($code, $type, $issueIndicator)
    {
        if ($this->checkAnyNotEmpty($type, $code, $issueIndicator)) {
            $this->transactionTypeCodeInfo[] = new TransactionData($type, $code, $issueIndicator);
        }
    }

    /**
     * @param string $indicator
     */
    protected function loadDocumentInfo($indicator)
    {
        if (!empty($indicator)) {
            $this->documentInfo = new SalesIndicator($indicator);
        }
    }
}
