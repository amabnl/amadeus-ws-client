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

namespace Amadeus\Client\Session\MsgClassmap;

/**
 * Message-specific Classmap Loader
 *
 * Responsible for checking and loading extra classmaps needed for activated messages.
 *
 * This is needed for mapping the request structures of OTA-based messages to the correct XSD entities.
 *
 * @package Amadeus\Client\Session\MsgClassmap
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class Loader
{
    /**
     * List of messages that need extra classmaps.
     *
     * @var array
     */
    protected static $extraClassMapNeeded = [
        'Hotel_MultiSingleAvailability'
    ];


    /**
     * Load extra ClassMaps needed for the messages and versions in the WSAP
     *
     * messagesAndVersions array:
     * [
     *   'PNR_Retrieve' => [
     *      'version' => '14.2',
     *      'wsdl' => 'a88a2bde'
     *   ],
     *   'Security_Authenticate' => [
     *      'version' => '6.1',
     *      'wsdl' => 'a88a2bde'
     *   ]
     *
     * @param array $messagesAndVersions
     * @return array
     */
    public static function loadMessagesSpecificClasses($messagesAndVersions)
    {
        $msgSpecificClassMap = [];

        foreach ($messagesAndVersions as $message => $messageInfo) {
            $msgSpecificClassMap = array_merge(
                $msgSpecificClassMap,
                self::loadClassMapForMessage($message, $messageInfo["version"])
            );
        }

        return $msgSpecificClassMap;
    }

    /**
     * Load Message-specific classmap
     *
     * @param string $message
     * @param string|float $version
     * @return array
     */
    protected static function loadClassMapForMessage($message, $version)
    {
        $classMap = [];

        if (in_array($message, self::$extraClassMapNeeded)) {
            $classMapLoader = self::makeClassMapLoader($message);

            if ($classMapLoader instanceof LoadInterface) {
                $classMap = $classMapLoader::loadClassMapForMessage($version);
            }
        }

        return $classMap;
    }

    /**
     * @param string $message
     * @return LoadInterface|null
     */
    protected static function makeClassMapLoader($message)
    {
        $loader = null;

        $section = substr($message, 0, strpos($message, '_'));
        $message = substr($message, strpos($message, '_') + 1);

        $loaderClass = __NAMESPACE__.'\\'.$section.'\\Loader'.$message;

        if (class_exists($loaderClass)) {
            /** @var LoadInterface $handler */
            $loader = new $loaderClass();
        }

        return $loader;
    }
}
