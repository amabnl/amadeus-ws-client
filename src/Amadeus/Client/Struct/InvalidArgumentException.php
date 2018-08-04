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

namespace Amadeus\Client\Struct;

/**
 * InvalidArgumentException
 *
 * This exception will be thrown when the Request we are trying to build cannot be built because of invalid
 * input provided.
 *
 * For example: an office ID which isn't valid, too many travellers in a PNR, ...
 * For a successful certification process, it's important that we do not call Amadeus web services messages
 * with data that we can already know is incorrect.
 *
 * @package Amadeus\Client\Struct
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class InvalidArgumentException extends \InvalidArgumentException
{
}
