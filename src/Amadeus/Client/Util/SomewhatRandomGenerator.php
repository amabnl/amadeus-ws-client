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

namespace Amadeus\Client\Util;

/**
 * SomewhatRandomGenerator - for generating somewhat random strings.
 *
 * Utilized to generate a base random string used to generate a Nonce
 * for the Soap Header 4 message headers.
 *
 * @package Amadeus\Client\Util
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SomewhatRandomGenerator
{
    /**
     * Generates a somewhat random string of a given length
     *
     * @param int $length
     * @return string
     */
    public static function generateSomewhatRandomString($length = 22)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        srand((double) microtime() * 1000000);
        $i = 0;
        $somewhatRandom = '';

        while ($i < $length) {
            $num = rand() % 60;
            $tmp = substr($chars, $num, 1);
            $somewhatRandom = $somewhatRandom.$tmp;
            $i++;
        }

        return $somewhatRandom;
    }
}
