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

namespace Test\Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareConvertCurrencyOptions;
use Amadeus\Client\Struct\Fare\ConvertCurrency;
use Amadeus\Client\Struct\Fare\MessageFunctionDetails;
use Amadeus\Client\Struct\Fare\MsgType;
use Amadeus\Client\Struct\Pnr\AddMultiElements\DateAndTimeDetails;
use Test\Amadeus\BaseTestCase;

/**
 * ConvertCurrencyTest
 *
 * @package Test\Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ConvertCurrencyTest extends BaseTestCase
{
    public function testCanConstructConvertCurrencyMessage()
    {
        $opt = new FareConvertCurrencyOptions([
            'from' => 'EUR',
            'to' => 'USD',
            'amount' => '200',
            'date' => \DateTime::createFromFormat('Y-m-d', '2015-12-25', new \DateTimeZone('UTC')),
            'rateOfConversion' => FareConvertCurrencyOptions::RATE_TYPE_BANKERS_SELLER_RATE
        ]);

        $message = new ConvertCurrency($opt);

        $this->assertEquals(MessageFunctionDetails::FARE_CURRENCY_CONVERSION, $message->message->messageFunctionDetails->messageFunction);

        $this->assertEquals(DateAndTimeDetails::QUAL_NOT_VALID_BEFORE, $message->conversionDate->dateAndTimeDetails[0]->qualifier);
        $this->assertEquals('251215', $message->conversionDate->dateAndTimeDetails[0]->date);

        $this->assertEquals(ConvertCurrency\ConversionRateDetails::RATE_TYPE_BANKERS_SELLER_RATE, $message->conversionRate->conversionRateDetails->rateType);

        $this->assertEquals(ConvertCurrency\SelectionDetails::OPTION_CONVERT_FROM, $message->conversionDetails[0]->conversionDirection->selectionDetails[0]->option);
        $this->assertEquals('EUR', $message->conversionDetails[0]->currencyInfo->conversionRateDetails->currency);
        $this->assertEquals('200', $message->conversionDetails[0]->amountInfo->monetaryDetails[0]->amount);
        $this->assertEquals(ConvertCurrency\MonetaryDetails::BASE_FARE, $message->conversionDetails[0]->amountInfo->monetaryDetails[0]->typeQualifier);

        $this->assertEquals(ConvertCurrency\SelectionDetails::OPTION_CONVERT_TO, $message->conversionDetails[1]->conversionDirection->selectionDetails[0]->option);
        $this->assertEquals('USD', $message->conversionDetails[1]->currencyInfo->conversionRateDetails->currency);
    }
}
