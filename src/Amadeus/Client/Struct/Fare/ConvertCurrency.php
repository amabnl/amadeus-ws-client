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

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareConvertCurrencyOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\ConvertCurrency\ConversionDate;
use Amadeus\Client\Struct\Fare\ConvertCurrency\ConversionDetails;
use Amadeus\Client\Struct\Fare\ConvertCurrency\ConversionRate;
use Amadeus\Client\Struct\Fare\ConvertCurrency\SelectionDetails;

/**
 * ConvertCurrency
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConvertCurrency extends BaseWsMessage
{
    /**
     * @var MsgType
     */
    public $message;

    /**
     * @var ConvertCurrency\ConversionDate
     */
    public $conversionDate;
    /**
     * @var ConvertCurrency\ConversionRate
     */
    public $conversionRate;
    /**
     * @var ConvertCurrency\ConversionDetails[]
     */
    public $conversionDetails = [];

    /**
     * Construct new FareConvertCurrency request object
     *
     * @param FareConvertCurrencyOptions $params
     */
    public function __construct(FareConvertCurrencyOptions $params)
    {
        $this->message = new MsgType(MessageFunctionDetails::FARE_CURRENCY_CONVERSION);

        $this->conversionRate = new ConversionRate($params->rateOfConversion);

        if ($params->date instanceof \DateTime) {
            $this->conversionDate = new ConversionDate($params->date);
        }

        $this->conversionDetails[] = new ConversionDetails(
            SelectionDetails::OPTION_CONVERT_FROM,
            $params->from,
            $params->amount
        );

        $this->conversionDetails[] = new ConversionDetails(
            SelectionDetails::OPTION_CONVERT_TO,
            $params->to
        );
    }
}
