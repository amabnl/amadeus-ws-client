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
 * FreeTextDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FreeTextDetails
{
    const QUALIFIER_LITERAL_TEXT = 3;
    const QUALIFIER_MUTUAL = "ZZZ";

    const SOURCE_MANUAL = "M";

    const ENCODING_DEFAULT = 1;
    const ENCODING_MUTUAL = "ZZZ";

    /**
     * self::QUALIFIER_*
     *
     * @var int|string
     */
    public $textSubjectQualifier = self::QUALIFIER_MUTUAL;

    /**
     * self::SOURCE_*
     *
     * @var string
     */
    public $source = self::SOURCE_MANUAL;

    /**
     * @var int|string
     */
    public $encoding = self::ENCODING_MUTUAL;

    /**
     * FreeTextDetails constructor.
     *
     * @param int|string $encoding
     */
    public function __construct($encoding = self::ENCODING_MUTUAL)
    {
        $this->encoding = $encoding;
    }
}
