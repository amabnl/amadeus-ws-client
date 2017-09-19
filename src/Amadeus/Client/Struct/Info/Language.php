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

namespace Amadeus\Client\Struct\Info;

/**
 * Language
 *
 * @package Amadeus\Client\Struct\Info
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Language
{
    /*
     * From the docs:
     * https://webservices.amadeus.com/extranet/viewUserGuide.do?id=16&flavourId=1&menuId=functional
     *
     * The language in which the location is expressed is ignored,
     * i.e. the search is performed and displayed in all languages
     */

    const QUAL_LANGUAGE_TRANSACTIONAL = 8;

    /**
     * @var int
     */
    public $languageCodeQualifier = self::QUAL_LANGUAGE_TRANSACTIONAL;

    /**
     * @var LanguageDetails
     */
    public $languageDetails;

    /**
     * Language constructor.
     *
     * @param string $languageCode
     */
    public function __construct($languageCode)
    {
        $this->languageDetails = new LanguageDetails($languageCode);
    }
}
