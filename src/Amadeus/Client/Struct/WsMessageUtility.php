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
 * WsMessageUtility - provides utility functions when constructing web service messages
 *
 * @package Amadeus\Client\Struct
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class WsMessageUtility
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
     * Check if all parameters to the current function are not empty
     *
     * @param mixed
     * @return boolean true if all parameters are not empty
     */
    protected function checkAllNotEmpty()
    {
        $foundEmpty = false;

        $args = func_get_args();

        foreach ($args as $arg) {
            if (empty($arg)) {
                $foundEmpty = true;
                break;
            }
        }

        return !$foundEmpty;
    }

    /**
     * Check if all parameters to the current function are integers
     *
     * @param mixed
     * @return boolean true if all parameters are integers
     */
    protected function checkAllIntegers()
    {
        $foundNonInt = false;

        $args = func_get_args();

        foreach ($args as $arg) {
            if (!is_int($arg)) {
                $foundNonInt = true;
                break;
            }
        }

        return !$foundNonInt;
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
