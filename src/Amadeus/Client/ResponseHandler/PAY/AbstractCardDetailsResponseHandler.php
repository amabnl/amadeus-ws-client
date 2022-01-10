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

namespace Amadeus\Client\ResponseHandler\PAY;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;

/**
 * VirtualCardDetailsBaseResponseHandler
 *
 * @package Amadeus\Client\ResponseHandler\PAY
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
abstract class AbstractCardDetailsResponseHandler extends StandardResponseHandler
{
    protected function fillCardDetails($analyzeResponse, $domXpath)
    {
        $cardNumber = $domXpath->query('//fop:PrimaryAccountNumber');
        if ($cardNumber->length > 0) {
            $analyzeResponse->response->Success->VirtualCard->Card->PrimaryAccountNumber =
                $cardNumber->item(0)->nodeValue;
        }

        $cvvNumber = $domXpath->query('//fop:CVV');
        if ($cvvNumber->length > 0) {
            $analyzeResponse->response->Success->VirtualCard->Card->CVV =
                $cvvNumber->item(0)->nodeValue;
        }

        $address = $domXpath->query('//fop:AddressVerificationSystemValue');
        if ($address->length > 0) {
            if (empty($analyzeResponse->response->Success->VirtualCard->Card->AddressVerificationSystemValue)) {
                $analyzeResponse->response->Success->VirtualCard->Card->AddressVerificationSystemValue = new \stdClass();
            }
            $analyzeResponse->response->Success->VirtualCard->Card->AddressVerificationSystemValue->Line =
                $address->item(0)->nodeValue;
            $analyzeResponse->response->Success->VirtualCard->Card->AddressVerificationSystemValue->CityName =
                $address->item(0)->getAttribute('CityName');
            $analyzeResponse->response->Success->VirtualCard->Card->AddressVerificationSystemValue->PostalCode =
                $address->item(0)->getAttribute('PostalCode');
            $analyzeResponse->response->Success->VirtualCard->Card->AddressVerificationSystemValue->Country =
                $address->item(0)->getAttribute('Country');
        }

        $limitations = $domXpath->query('//pay:Limitations');
        if ($limitations->length > 0) {
            $limitationsNodes = $limitations->item(0)->childNodes;
            $analyzeResponse->response->Success->VirtualCard->AllowedTransactions =
                $limitationsNodes->item(0)->getAttribute('Maximum');
            $analyzeResponse->response->Success->VirtualCard->ValidityPeriod =
                $limitationsNodes->item(1)->getAttribute('EndDate');
        }


        $balance = $domXpath->query('//pay:Value[@Type=\'AvailableBalance\']');
        if ($balance->length > 0) {
            $analyzeResponse->response->Success->VirtualCard->Amount =
                $balance->item(0)->getAttribute('Amount');
            $analyzeResponse->response->Success->VirtualCard->DecimalPlaces =
                $balance->item(0)->getAttribute('DecimalPlaces');
            $analyzeResponse->response->Success->VirtualCard->CurrencyCode =
                $balance->item(0)->getAttribute('CurrencyCode');
        }

        $balance = $domXpath->query('//pay:Value[@Type=\'OnCard\']');
        if ($balance->length > 0) {
            $analyzeResponse->response->Success->VirtualCard->Amount =
                $balance->item(0)->getAttribute('Amount');
            $analyzeResponse->response->Success->VirtualCard->DecimalPlaces =
                $balance->item(0)->getAttribute('DecimalPlaces');
            $analyzeResponse->response->Success->VirtualCard->CurrencyCode =
                $balance->item(0)->getAttribute('CurrencyCode');
        }

        return $analyzeResponse;
    }
}
