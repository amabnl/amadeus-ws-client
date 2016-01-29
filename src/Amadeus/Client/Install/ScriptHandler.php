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

namespace Amadeus\Client\Install;

use Composer\Script\Event;

/**
 * Install scripts
 *
 * Currently one script will be ran post-install:
 * To generate a unique message nonce base which will be used to generate a random Nonce string.
 * This will avoid (very unlikely) collisions with other users of this library: A Message Nonce must be unique on the
 * Amadeus WS server side.
 *
 * @package Amadeus\Client
 */
class ScriptHandler
{
    /**
     * Relative path from package root to nonce base file
     *
     * @var string
     */
    protected static $nonceFilePath = "conf".DIRECTORY_SEPARATOR."noncebase.txt";

    /**
     * Package root
     *
     * @var string
     */
    protected static $root;

    /**
     * What length we want the nonce base to be.
     *
     * @var int
     */
    protected static $nonceBaseLength = 22;

    /**
     * Checks if a file exists containing a valid "random" nonce base string.
     *
     * If not, it will generate a string and save the file.
     *
     * @param Event $event
     */
    public static function checkAndGenerateNonceBase(Event $event)
    {
        self::$root = dirname(dirname(dirname(dirname(dirname(__FILE__)))));

        if (self::haveNonceBase($event) === false) {
            self::generateAndSaveNonceBase($event);
        }
    }

    /**
     * @param Event $event
     * @return bool
     */
    protected static function haveNonceBase($event)
    {
        $nonceBaseOk = false;
        $fullPath = self::$root.DIRECTORY_SEPARATOR.self::$nonceFilePath;

        if (file_exists($fullPath)) {
            $nonceVal = file_get_contents($fullPath);

            $nonceBaseOk = (mb_strlen($nonceVal) === self::$nonceBaseLength);
            /*if ($nonceBaseOk) {
                $event->getIO()->write('A file with a valid nonce base exists.');
            }*/
        }

        return $nonceBaseOk;
    }

    /**
     * @param Event $event
     */
    protected static function generateAndSaveNonceBase($event)
    {
        $fullPath = self::$root.DIRECTORY_SEPARATOR.self::$nonceFilePath;
        $randomString = SomewhatRandomGenerator::generateSomewhatRandomString(self::$nonceBaseLength);

        file_put_contents($fullPath, $randomString);

        $event->getIO()->write('GENERATING NONCE BASE FILE WITH STRING: ' .$randomString);
    }
}
