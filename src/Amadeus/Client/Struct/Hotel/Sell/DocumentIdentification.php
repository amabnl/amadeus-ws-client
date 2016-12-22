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

namespace Amadeus\Client\Struct\Hotel\Sell;

/**
 * DocumentIdentification
 *
 * @package Amadeus\Client\Struct\Hotel\Sell
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class DocumentIdentification
{
    const TYPE_CEDULA_ARGENTINIAN_ID = "CED";
    /**
     *  NUMBER FOR BRAZILIANS AND RESIDENT ALIENS WHO PAY TAXES IN BRAZIL
     */
    const TYPE_CADASTRO_DE_PESSOAS_FISICAS = "CPF";
    const TYPE_LOCAL_ID_DOCUMENT = "ID";
    const TYPE_NATIONAL_ID_CARD = "NI";
    const TYPE_OTHER_ID_DOCUMENT = "OTH";
    const TYPE_PASSPORT = "PT";
    const TYPE_VISA = "VI";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $number;

    /**
     * DocumentIdentification constructor.
     *
     * @param string $number
     * @param string $type
     */
    public function __construct($number, $type)
    {
        $this->type = $type;
        $this->number = $number;
    }
}
