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
 * Fop
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Fop
{
    const IDENT_CASH = "CA";
    const IDENT_CHECK = "CK";
    const IDENT_CREDITCARD = "CC";
    const IDENT_MISC = "MS";

    /**
     * @var string
     */
    public $identification;
    /**
     * @var mixed
     */
    public $amount;
    /**
     * @var mixed
     */
    public $creditCardCode;
    /**
     * @var mixed
     */
    public $accountNumber;
    /**
     * @var mixed
     */
    public $expiryDate;
    /**
     * @var mixed
     */
    public $approvalCode;
    /**
     * @var mixed
     */
    public $customerAccountNumber;
    /**
     * @var mixed
     */
    public $paymentTimeReference;
    /**
     * @var mixed
     */
    public $freetext;
    /**
     * @var mixed
     */
    public $currencyCode;

    /**
     * @param string|null $identification One of the constants IDENT_* defined in this class.
     */
    public function __construct($identification = null)
    {
        if (!is_null($identification) && strlen($identification) == 2 && self::isValidFopType($identification)) {
            $this->identification = $identification;
        }
    }

    /**
     * Check if the given Form Of Payment type is valid
     *
     * @param string $fopType
     * @return boolean
     */
    public static function isValidFopType($fopType)
    {
        return ($fopType == self::IDENT_CASH
            || $fopType == self::IDENT_CHECK
            || $fopType == self::IDENT_CREDITCARD
            || $fopType == self::IDENT_MISC);
    }
}
