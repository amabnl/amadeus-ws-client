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

namespace Amadeus\Client\Struct\Security;

/**
 * PasswordInfo
 *
 * @package Amadeus\Client\Struct\Security
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PasswordInfo
{
    /**
     * Definition of Password Data type "EDIFACT DATA"
     *
     * See Amadeus Core Webservices documentation
     * [DATA TYPE codesets (Ref: 116Z 1A 02.1.8)]
     *
     * @var string
     */
    const DATA_TYPE_EDIFACT = 'E';
    /**
     * Definition of Password Data type "BINARY DATA"
     *
     * See Amadeus Core Webservices documentation
     * [DATA TYPE codesets (Ref: 116Z 1A 02.1.8)]
     *
     * @var string
     */
    const DATA_TYPE_BINARY = 'B';
    /**
     * When using "EDIFACT DATA" type, password length value must be 12.
     *
     * @var int
     */
    const PASSWORD_LENGTH_EDIFACT = 12;
    /**
     * When using "BINARY DATA" type, password length value must be 40.
     *
     * @var int
     */
    const PASSWORD_LENGTH_BINARY = 40;

    /**
     * @var int
     */
    public $dataLength;
    /**
     * @var string
     */
    public $dataType;
    /**
     * @var string
     */
    public $binaryData;

    /**
     * PasswordInfo constructor.
     *
     * @param string $passwordData
     * @param int $passwordLength
     * @param string $type
     */
    public function __construct(
        $passwordData,
        $passwordLength = self::PASSWORD_LENGTH_EDIFACT,
        $type = self::DATA_TYPE_EDIFACT
    ) {
        $this->binaryData = $passwordData;
        $this->dataLength = $passwordLength;
        $this->dataType = $type;
    }
}
