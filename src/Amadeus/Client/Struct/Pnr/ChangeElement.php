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

use Amadeus\Client\RequestOptions\PnrChangeElementOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Command\LongTextString;
use Amadeus\Client\Struct\Command\MessageAction;

/**
 * ChangeElement
 *
 * @package Amadeus\Client\Struct\Pnr
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class ChangeElement extends BaseWsMessage
{
    /**
     * @var MessageAction
     */
    public $messageAction;

    /**
     * @var LongTextString
     */
    public $longTextString;

    /**
     * @param PnrChangeElementOptions $requestOptions
     */
    public function __construct(PnrChangeElementOptions $requestOptions)
    {
        $this->messageAction = new MessageAction();
        $this->longTextString = new LongTextString($requestOptions->entry);
    }
}
