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

namespace Amadeus\Client\Struct\Fare\MasterPricer;

/**
 * TicketingPriceScheme
 *
 * @package Amadeus\Client\Struct\Fare\MasterPricer
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class TicketingPriceScheme
{
    /**
     * Identification number.
     *
     * @var string
     */
    public $referenceNumber;

    /**
     * Name identifying a product.
     *
     * @var string (OPTIONAL)
     */
    public $name;

    /**
     * Provides information regarding the status of an instruction.
     *
     * @var string
     */
    public $status;

    /**
     * Free text field available to the message sender for information.
     *
     * @var string (OPTIONAL)
     */
    public $description;
}
