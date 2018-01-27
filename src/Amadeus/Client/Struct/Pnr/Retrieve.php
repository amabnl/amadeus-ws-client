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

namespace Amadeus\Client\Struct\Pnr;

use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Structure class for representing the PNR_Retrieve request message
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Retrieve extends BaseWsMessage
{
    const RETR_TYPE_BY_RECLOC = 2;
    const RETR_TYPE_ACTIVE_PNR = 1;
    const RETR_TYPE_FROM_LIST = 25;
    const RETR_TYPE_BY_OFFICE_AND_NAME = 3;
    const RETR_TYPE_BY_SERVICE_AND_NAME = 4;
    const RETR_TYPE_BY_FREQUENT_TRAVELLER = 5;
    const RETR_TYPE_BY_ACCOUNT_NUMBER = 6;
    const RETR_TYPE_BY_CUSTOMER_PROFILE = 7;
    const RETR_TYPE_BY_INSURANCE_POLICY_NUMBER = 8;
    const RETR_TYPE_BY_NUMERIC_RECORD_LOCATOR = 9;
    const RETR_TYPE_FOR_TICKETING = 95;
    
    /**
     * @var Retrieve\Settings
     */
    public $settings;

    /**
     * @var Retrieve\RetrievalFacts
     */
    public $retrievalFacts;
    
    /**
     * Construct PNR_Retrieve message
     *
     * @param PnrRetrieveOptions $options
     */
    public function __construct($options)
    {
        // Determine retrieval type depending on which options were provided.
        // Also, maintain backwards compatibility with how this message previously worked:

        // Warning, this won't work when combining options. In that case you'll get a soapfault from Amadeus.

        if (is_null($options->retrievalType)) {
            if (!empty($options->recordLocator)) {
                $options->retrievalType = self::RETR_TYPE_BY_RECLOC;
            } elseif (!empty($options->customerProfile)) {
                $options->retrievalType = self::RETR_TYPE_BY_CUSTOMER_PROFILE;
            } elseif (!empty($options->accountNumber)) {
                $options->retrievalType = self::RETR_TYPE_BY_ACCOUNT_NUMBER;
            } elseif (!empty($options->frequentTraveller)) {
                $options->retrievalType = self::RETR_TYPE_BY_FREQUENT_TRAVELLER;
            } elseif ($this->checkAnyNotEmpty($options->service)) {
                $options->retrievalType = self::RETR_TYPE_BY_SERVICE_AND_NAME;
            } elseif ($this->checkAnyNotEmpty($options->lastName, $options->officeId)) {
                $options->retrievalType = self::RETR_TYPE_BY_OFFICE_AND_NAME;
            } elseif (!$options->recordLocator) {
                $options->retrievalType = self::RETR_TYPE_ACTIVE_PNR;
            }
        }

        $this->retrievalFacts = new Retrieve\RetrievalFacts($options);
    }
}
