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

use Amadeus\Client\RequestOptions\PnrNameChangeOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Pnr\NameChange\EnhancedPassengerGroup;
use Amadeus\Client\Struct\Pnr\NameChange\TransactionCode;

/**
 * PNR_NameChange Request stucture
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class NameChange extends BaseWsMessage
{
    /**
     * @var NameChange\TransactionCode
     */
    public $transactionCode;

    /**
     * @var NameChange\EnhancedPassengerGroup[]
     */
    public $enhancedPassengerGroup = [];

    /**
     * NameChange constructor.
     *
     * @param PnrNameChangeOptions $options
     */
    public function __construct(PnrNameChangeOptions $options)
    {
        $this->transactionCode = new TransactionCode($options->operation);

        foreach ($options->passengers as $passenger) {
            $this->enhancedPassengerGroup[] = new EnhancedPassengerGroup($passenger);
        }
    }
}
