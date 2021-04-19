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

namespace Amadeus\Client\RequestOptions\Pay;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Period
 *
 * @package Amadeus\Client\RequestOptions\Pay
 * @author Konstantin Bogomolov <bog.konstantin@gmail.com>
 */
class Period extends LoadParamsFromArray
{
    /**
     * YYYY-MM-DD or YYYY-MM-DD-hh:mm:ss
     *
     * @var string
     */
    public $start;

    /**
     * YYYY-MM-DD or YYYY-MM-DD-hh:mm:ss
     *
     * @var string
     */
    public $end;
}
