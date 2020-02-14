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
namespace Amadeus\Client\Struct\Service\BookPriceService;

use Amadeus\Client\RequestOptions\Service\BookPriceService\Identifier as IdentifierOptions;

/**
 * Identifier
 *
 * @package Amadeus\Client\Struct\Service\BookPriceService
 * @author Mike Hernas <mike@ahoy.io>
 */
class Identifier
{
    /**
     * @var string
     */
    public $bookingMethod;

    /**
     * @var string|null
     */
    public $RFIC = null;

    /**
     * @var string|null
     */
    public $RFISC = null;

    /**
     * @var string|null
     */
    public $Code = null;

    /**
     * Identifier constructor.
     *
     * @param IdentifierOptions $identifierOptions
     */
    public function __construct($identifierOptions)
    {
        $this->bookingMethod = $identifierOptions->bookingMethod;

        if (!empty($identifierOptions->code)) {
            $this->Code = $identifierOptions->code;
        }

        if (!empty($identifierOptions->RFISC)) {
            $this->RFISC = $identifierOptions->RFISC;
        }

        if (!empty($identifierOptions->RFIC)) {
            $this->RFIC = $identifierOptions->RFIC;
        }
    }
}
