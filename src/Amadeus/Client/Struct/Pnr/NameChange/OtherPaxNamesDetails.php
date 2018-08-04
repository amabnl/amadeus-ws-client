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

namespace Amadeus\Client\Struct\Pnr\NameChange;

/**
 * OtherPaxNamesDetails
 *
 * @package Amadeus\Client\Struct\Pnr\NameChange
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class OtherPaxNamesDetails
{
    /**
     * NN1 Romanizable Native Name
     * NN2 Non-Romanizable Native Name
     * UN  Universal Name
     *
     * @var string
     */
    public $nameType;

    /**
     * N No (not the reference name)
     * Y Yes (reference name)
     *
     * @var string
     */
    public $referenceName;

    /**
     * @var string
     */
    public $surname;

    /**
     * @var string
     */
    public $givenName;

    /**
     * @var string
     */
    public $title;

    /**
     * OtherPaxNamesDetails constructor.
     *
     * @param string $surname
     * @param string $givenName
     * @param string|null $title
     */
    public function __construct($surname, $givenName, $title = null)
    {
        $this->surname = $surname;
        $this->givenName = $givenName;
        $this->title = $title;
    }
}
