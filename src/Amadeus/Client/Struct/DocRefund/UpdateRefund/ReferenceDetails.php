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

namespace Amadeus\Client\Struct\DocRefund\UpdateRefund;

/**
 * ReferenceDetails
 *
 * @package Amadeus\Client\Struct\DocRefund\UpdateRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ReferenceDetails
{
    const TYPE_DATA_SOURCE = "DIS";
    const TYPE_FZ_REVENUE_ATTRIBUTION_NUMBER = "FZR";
    const TYPE_INVOICE_NUMBER = "INV";
    const TYPE_TKT_INDICATOR = "TKT";

    const TYPE_VALIDATION_CERTIFICATE_USED_FOR_STAFF = "VAC";

    const TYPE_PRINTER_MNEMONIC = "MNE";

    /**
     * self::TYPE_*
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $value;

    /**
     * ReferenceDetails constructor.
     *
     * @param string $value
     * @param string $type self::TYPE_*
     */
    public function __construct($value, $type)
    {
        $this->type = $type;
        $this->value = $value;
    }
}
