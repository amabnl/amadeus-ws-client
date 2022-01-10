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

use Amadeus\Client\RequestOptions\PayListVirtualCardsOptions;

/**
 * Period
 *
 * @package Amadeus\Client\Struct\Pay
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class Period
{
    /**
     * @var string
     */
    public $Start;

    /**
     * @var string
     */
    public $End;

    /**
     * @var string
     */
    public $EventType = 'Creation';

    /**
     * Period constructor.
     *
     * @param PayListVirtualCardsOptions $params
     */
    public function __construct(PayListVirtualCardsOptions $params)
    {
        if ($params->Period->start !== null) {
            $this->Start = $params->Period->start; // TODO fix
        }

        if ($params->Period->end !== null) {
            $this->End = $params->Period->end; // TODO fix
        }
    }
}
