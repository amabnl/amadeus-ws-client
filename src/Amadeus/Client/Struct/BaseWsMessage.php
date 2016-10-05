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
 * BaseWsMessage Base Web Service message class
 *
 * @package Amadeus\Client\Struct
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class BaseWsMessage
{
    /**
     * Check if any parameter to the current function is not empty
     *
     * @param mixed
     * @return boolean true if at least 1 parameter is not empty
     */
    protected function checkAnyNotEmpty()
    {
        $foundNotEmpty = false;

        $args = func_get_args();

        foreach ($args as $arg) {
            if (!empty($arg)) {
                $foundNotEmpty = true;
                break;
            }
        }

        return $foundNotEmpty;
    }

    /**
     * Check if any parameter to the current function is true
     *
     * @param mixed
     * @return boolean true if at least 1 parameter is true
     */
    protected function checkAnyTrue()
    {
        $foundTrue = false;

        $args = func_get_args();

        foreach ($args as $arg) {
            if ($arg === true) {
                $foundTrue = true;
                break;
            }
        }

        return $foundTrue;
    }
}
