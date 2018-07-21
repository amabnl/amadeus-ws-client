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
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class MsgBodyExtractor
{
    /**
     * Regular expression for extracting the Soap Envelope Body's content.
     *
     * @var string
     */
    const REGEXP_SOAP_ENVELOPE_CONTENTS = "|\\<SOAP-ENV:Body\\>(.*?)\\<\\/SOAP-ENV:Body\\>|s";

    /**
     * Regular expression for extracting the Soap Envelope Body's content - legacy format for Soap Header v2 and older
     *
     * @var string
     */
    const REGEXP_SOAP_ENVELOPE_CONTENTS_LEGACY = "|\\<soap:Body\\>(.*?)\\<\\/soap:Body\\>|s";

    /**
     * Extracts the message content from the soap envelope (i.e. everything under the soap body)
     *
     * @param string $soapResponse
     * @return string|null
     */
    public static function extract($soapResponse)
    {
        $messageBody = null;
        $matches = [];

        if (preg_match(self::REGEXP_SOAP_ENVELOPE_CONTENTS, $soapResponse, $matches) === 1) {
            $messageBody = $matches[1];
        }

        if (empty($messageBody)) {
            if (preg_match(self::REGEXP_SOAP_ENVELOPE_CONTENTS_LEGACY, $soapResponse, $matches) === 1) {
                $messageBody = $matches[1];
            }
        }

        return $messageBody;
    }
}
