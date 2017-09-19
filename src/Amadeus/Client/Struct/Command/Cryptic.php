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

namespace Amadeus\Client\Struct\Command;

use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Command_Cryptic message structure
 *
 * @package Amadeus\Client\Struct\Command
 * @author dieter <dermikagh@gmail.com>
 */
class Cryptic extends BaseWsMessage
{
    /**
     * @var mixed
     */
    public $originatorDetailSection;
    /**
     * @var MessageAction
     */
    public $messageAction;
    /**
     * @var mixed
     */
    public $numberOfUnits;
    /**
     * @var mixed
     */
    public $intelligentWorkstationInfo;
    /**
     * @var LongTextString
     */
    public $longTextString;

    /**
     * @param string|null $command
     */
    public function __construct($command = null)
    {
        $this->messageAction = new MessageAction();
        $this->longTextString = new LongTextString($command);
    }
}
