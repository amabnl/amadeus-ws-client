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
use stdClass;

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
                $analyzeResponse->response->Success->VirtualCard->Card->AddressVerificationSystemValue = new stdClass();
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
            $limitationsNodesArrayData = $this->nodeToArray($limitations->item(0));
            $analyzeResponse->response->Success->VirtualCard->AllowedTransactions =
                $limitationsNodesArrayData['pay:AllowedTransactions'][0]['Maximum'];
            $analyzeResponse->response->Success->VirtualCard->ValidityPeriod =
                $limitationsNodesArrayData['pay:ValidityPeriod'][0]['EndDate'];
        }

        $balance = $domXpath->query('//pay:Value[@Type=\'AvailableBalance\']');

        if ($balance->length === 0) {
            // OnCard = amount actually on card, but some responses may not contain such type
            $balance = $domXpath->query('//pay:Value[@Type=\'OnCard\']');
        }

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

    /**
     * Using this method because otherwise uploaded XML used for unit-tests for unknown reason does not recognize:
     * $limitations = $domXpath->query('//pay:Limitations');
     * $limitationsNodes = $limitations->item(0)->childNodes;
     * $limitationsNodes->item(0)->getAttribute('Maximum'); <<<--- Call to undefined method DOMText::getAttribute()
     *
     * So using this nodeToArray() method which works in both unit-tests and real SOAP client
     * https://www.php.net/manual/en/class.domdocument.php#101014
     * @param $node
     * @return array
     */
    protected function nodeToArray($node): array
    {
        $result = [];

        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $result[$attr->nodeName] = $attr->nodeValue;
            }
        }

        if ($node->hasChildNodes()) {
            foreach ($node->childNodes as $childNode) {
                if ($childNode->nodeType !== XML_TEXT_NODE) {
                    $result[$childNode->nodeName][] = $this->nodeToArray($childNode);
                }
            }
        }

        return $result;
    }
}
