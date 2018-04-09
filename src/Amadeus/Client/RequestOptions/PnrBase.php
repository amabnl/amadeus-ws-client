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

namespace Amadeus\Client\RequestOptions;

/**
 * PNR_* base request constants
 *
 * @package Amadeus\Client\RequestOptions
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PnrBase extends Base
{
    const ACTION_NO_PROCESSING = 0;

    const ACTION_END_TRANSACT = 10;

    const ACTION_END_TRANSACT_RETRIEVE = 11;

    const ACTION_END_TRANSACT_CHANGE_ADV_CODES = 12;

    const ACTION_END_TRANSACT_RETRIEVE_CHANGE_ADV_CODES = 13;

    const ACTION_END_TRANSACT_SPLIT = 14;

    const ACTION_CANCEL_ITIN_END_TRANSACT = 15;

    const ACTION_CANCEL_ITIN_END_TRANSACT_RETRIEVE = 16;

    const ACTION_IGNORE = 20;

    const ACTION_IGNORE_RETRIEVE = 21;

    const ACTION_STOP_EOT_ON_SELL_ERROR = 267;

    const ACTION_WARNING_AT_EOT = 30;

    const ACTION_REPLY_SHORT_MESSAGE = 50;
}
