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
 * MsgBodyExtractor
 *
 * @package Amadeus\Client\Util
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MsgBodyExtractor
{
    /**
     * Extracts the message content from the soap envelope (i.e. everything under the soap body)
     *
     * @param string $soapResponse
     * @return string|null
     */
    public function extract($soapResponse)
    {
        $messageBody = null;

        $messageBody = $this->getStringBetween($soapResponse, '<SOAP-ENV:Body>', '</SOAP-ENV:Body>');

        if (empty($messageBody) || false === $messageBody) {
            $messageBody = $this->getStringBetween($soapResponse, '<soap:Body>', '</soap:Body>');
        }

        return $messageBody;
    }

    /**
     * Get substring between two strings
     *
     * @param $string
     * @param $start
     * @param $end
     * @return bool|string
     */
    private function getStringBetween($string, $start, $end)
    {
        $startPos = strpos($string, $start) + strlen($start);

        $endPos = strlen($string) - strpos($string, $end);

        return substr($string, $startPos, -$endPos);
    }
}
