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

namespace Amadeus\Client\Struct\Pay;

use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\RequestOptions\PayListVirtualCardsOptions;

/**
 * ListVirtualCards
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class ListVirtualCards extends BaseWsMessage
{
    public $Version = '2.0';

    /**
     * @var string
     */
    public $SubType;

    /**
     * @var string
     */
    public $VendorCode;

    /**
     * @var AmountRange
     */
    public $AmountRange;

    /**
     * @var string
     */
    public $CurrencyCode;

    /**
     * @var \SoapVar
     */
    public $Period;

    /**
     * @var string
     */
    public $CardStatus;

    /**
     * @var Reservation
     */
    public $Reservation;

    /**
     * ListVirtualCards constructor.
     * @param PayListVirtualCardsOptions $params
     */
    public function __construct(PayListVirtualCardsOptions $params)
    {
        if ($params->SubType !== null) {
            $this->SubType = $params->SubType;
        }

        if ($params->VendorCode !== null) {
            $this->VendorCode = $params->VendorCode;
        }

        if ($params->AmountRange !== null) {
            $this->AmountRange = new AmountRange($params);
        }

        if ($params->CurrencyCode !== null) {
            $this->CurrencyCode = $params->CurrencyCode;
        }

        $period = $params->Period;

        if ($period !== null) {
            /**
             * For unknown reason, the SOAP client does not generate proper XML with attributes for Period node.
             * Expected to have <Period Start="2017-04-01" End="2017-04-1" EventType="Creation" />
             * But generated XML never contains "Start" and "End" attributes (by the way "EventType" present).
             * This SoapVar solution is not ideal, but at least it works.
             */
            $periodAttributes = array_filter([
                'Start' => $period->start?->format('Y-m-d'),
                'End' => $period->end?->format('Y-m-d'),
                'EventType' => $period->eventType,
            ]);

            // Result is <ns1:Period Start="2017-04-01" End="2017-04-1" EventType="Creation" />
            $xml = sprintf(
                '<ns1:Period %s ></ns1:Period>',
                implode(
                    ' ',
                    array_map(
                        static fn (string $key, string $value): string => sprintf('%s="%s"', $key, $value),
                        array_keys($periodAttributes),
                        $periodAttributes,
                    ),
                ),
            );
            $this->Period = new \SoapVar($xml, XSD_ANYXML);
        }

        if ($params->CardStatus !== null) {
            $this->CardStatus = $params->CardStatus;
        }

        if ($params->Reservation !== null) {
            $this->Reservation = new Reservation($params);
        }
    }
}
