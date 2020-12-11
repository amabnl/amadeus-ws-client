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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

use Amadeus\Client\RequestOptions\Pnr\Element\Security as SecurityReOpts;

/**
 * PnrSecurity
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrSecurity
{

    /**
     * Amadeus global core office identification
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Codeset for General indicator, coded (Ref: 107P 1A 98.1.13)]
     *
     * @var string
     */
    const INDICATOR_GLOBAL = 'G';

    /**
     * IATA number
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Codeset for General indicator, coded (Ref: 107P 1A 98.1.13)]
     *
     * @var string
     */
    const INDICATOR_IATA_NUMBER = 'I';

    /**
     * Pseudo-office identification
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Codeset for General indicator, coded (Ref: 107P 1A 98.1.13)]
     *
     * @var string
     */
    const INDICATOR_PSEUDO_OFFICE = 'P';



    /**
     * @var Security[]
     */
    public $security;

    public $securityInfo;

    /**
     * @var string
     */
    public $indicator;

    public function __construct(SecurityReOpts $securityReq)
    {
        $this->indicator = $securityReq->indicator;
        $this->security[] = new Security($securityReq->identification, $securityReq->accessMode);
    }
}