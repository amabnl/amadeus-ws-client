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

namespace Amadeus\Client\Struct\Fop;

/**
 * TdsBlbData
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class TdsBlbData
{
    const DATATYPE_BINARY = "B";
    const DATATYPE_EDIFACT = "E";

    /**
     * Binary Data length
     *
     * @var int
     */
    public $dataLength;

    /**
     * self::DATATYPE_*
     *
     * @var string
     */
    public $dataType = self::DATATYPE_BINARY;

    /**
     * Base64 encoded data
     *
     * @var string
     */
    public $binaryData;

    /**
     * TdsBlbData constructor.
     *
     * @param string $binaryData
     * @param string $dataType
     * @param int|null $dataLength
     */
    public function __construct($binaryData, $dataType, $dataLength)
    {
        if (!is_null($dataLength)) {
            $this->dataLength = $dataLength;
        } else {
            $this->dataLength = mb_strlen(base64_decode($binaryData));
        }
        $this->dataType = $dataType;
        $this->binaryData = $binaryData;
    }
}
