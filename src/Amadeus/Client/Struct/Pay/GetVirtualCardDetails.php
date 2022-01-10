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

namespace Amadeus\Client\Struct\Pay;

use Amadeus\Client\RequestOptions\PayGetVirtualCardDetailsOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\InvalidArgumentException;

/**
 * GetVirtualCardDetails
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class GetVirtualCardDetails extends BaseWsMessage
{
    const FILTER_FULL = 'Full';
    const FILTER_LIGHT = 'Light';

    public $Version = '2.0';

    public $References;

    public $DisplayFilter;

    /**
     * GetVirtualCardDetails constructor.
     * @param PayGetVirtualCardDetailsOptions $params
     * @param string|int                      $version
     */
    public function __construct(PayGetVirtualCardDetailsOptions $params, $version)
    {
        if ($params->amadeusReference !== null) {
            $this->References[] = new Reference(Reference::TYPE_AMADEUS, $params->amadeusReference);
        }

        if ($params->externalReference !== null) {
            $this->References[] = new Reference(Reference::TYPE_EXTERNAL, $params->externalReference);
        }

        if (empty($this->References)) {
            throw new InvalidArgumentException('At least one Reference in GetVirtualCardDetails options is mandatory');
        }

        if (null !== $params->displayFilter) {
            $this->DisplayFilter = $params->displayFilter;
        }
    }
}
