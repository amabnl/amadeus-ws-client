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

/**
 * FreetextDetail
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class FreetextDetail
{
    /**
     * Free text qualifier "Literal Text"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Text subject qualifier codesets (Ref: 4451 1A 98.1.1)]
     * @var string;
     */
    const QUALIFIER_LITERALTEXT = "3";
    /**
     * Free text type "Receive from"
     *
     * See documentation Amadeus Core webservices
     * Functional documentation PNR_AddMultiElements
     * [Information type, coded codesets (Ref: 9980 1A 98.1.17)]
     * @var string
     */
    const TYPE_RECEIVE_FROM = "P22";

    /**
     * @var string
     */
    public $subjectQualifier;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $status;
    /**
     * @var string
     */
    public $companyId;

    /**
     * @param string $type
     * @param string $subQual
     */
    public function __construct($type = self::TYPE_RECEIVE_FROM, $subQual = self::QUALIFIER_LITERALTEXT)
    {
        $this->type = $type;
        $this->subjectQualifier = $subQual;
    }
}
