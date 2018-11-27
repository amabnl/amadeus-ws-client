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

namespace Amadeus\Client\RequestOptions\Fare\MasterPricer;

use Amadeus\Client\LoadParamsFromArray;

/**
 * FormOfPaymentDetails
 *
 * @package Amadeus\Client\RequestOptions\Fare\MasterPricer
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class FormOfPaymentDetails extends LoadParamsFromArray
{
    const TYPE_BEHALF_EXCHANGE = 'AGT';
    const TYPE_CASH = 'CA';
    const TYPE_CREDIT_CARD = 'CC';
    const TYPE_CHECK = 'CK';
    const TYPE_GOVERNMENT_TRANSPORTATION_REQUEST = 'GR';
    const TYPE_MISCELLANEOUS = 'MS';
    const TYPE_NON_REFUNDABLE = 'NR';
    const TYPE_PREPAID_TICKET_ADVICE = 'PT';
    const TYPE_SINGLE_GOVERNMENT_TRANSPORTATION_REQUEST = 'SGR';
    const TYPE_UNITED_NATIONS_TRANSPORTATION_REQUEST = 'UN';

    /**
     * Identification of a form of payment type.
     *
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * Number of monetary units.
     *
     * @var int|float|string|null
     */
    public $chargedAmount;

    /**
     * Identification number.
     *
     * @var string
     */
    public $creditCardNumber;
}
