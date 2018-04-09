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

namespace Amadeus\Client\Struct\Fop;

/**
 * TdsReferenceDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TdsReferenceDetails
{
    const REF_PARES = 'PARES';
    const REF_VISA_CARD = 'CAVV';
    const REF_MASTERCARD = 'AVVV';
    const REF_THREEDS_TRANSACTION_IDENTIFIER = 'XID';

    /**
     * @var string
     */
    public $value;

    /**
     * TdsReferenceDetails constructor.
     *
     * @param string $value self::REF_*
     */
    public function __construct($value)
    {
        $this->value = $value;
    }
}
