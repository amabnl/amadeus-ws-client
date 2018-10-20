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

use Amadeus\Client\RequestOptions\SalesReportsDisplayDailyOrSummarizedReportOptions;
use Amadeus\Client\Struct\SalesReports\DisplayDailyOrSummarizedReport\SalesReportIdentification;
use Amadeus\Client\Struct\SalesReports\DisplayQueryReport\CurrencyInfo;

/**
 * DisplayDailyOrSummarizedReport
 *
 * @package Amadeus\Client\Struct\SalesReports
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class DisplayDailyOrSummarizedReport extends DisplayQueryReport
{
    /**
     * @var SalesReportIdentification
     */
    public $salesReportIdentification;

    /**
     * @var DisplayQueryReport\CurrencyInfo
     */
    public $currency;

    /**
     * @var string
     */
    public $dummy;

    /**
     * DisplayDailyOrSummarizedReport constructor.
     *
     * @param SalesReportsDisplayDailyOrSummarizedReportOptions $options
     */
    public function __construct(SalesReportsDisplayDailyOrSummarizedReportOptions $options)
    {
        $this->loadRequestOptions($options->requestOptions);

        $this->loadAgencySource($options->agencySourceType, $options->agencyIataNumber, $options->agencyOfficeId);

        $this->loadAgent($options->agentCode);

        $this->loadValidatingCarrier($options->validatingCarrier);

        $this->loadDateRange($options->startDate, $options->endDate);

        $this->loadDate($options->specificDateType, $options->specificDate);

        $this->loadCurrency($options->currencyType, $options->currency);

        $this->loadSalesIndicator($options->salesIndicator);

        $this->loadSalesReportIdentification(
            $options->salesReportIdentificationNumber,
            $options->salesReportIdentificationType
        );
    }

    /**
     * @param string $type
     * @param string $currency
     */
    protected function loadCurrency($type, $currency)
    {
        if ($this->checkAnyNotEmpty($type, $currency)) {
            $this->currency = new CurrencyInfo($type, $currency);
        }
    }

    protected function loadSalesReportIdentification($number, $type)
    {
        if ($this->checkAllNotEmpty($number, $type)) {
            $this->salesReportIdentification = new SalesReportIdentification($number, $type);
        }
    }
}
