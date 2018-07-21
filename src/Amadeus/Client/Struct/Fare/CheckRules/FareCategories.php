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

namespace Amadeus\Client\Struct\Fare\CheckRules;

/**
 * FareCategories
 *
 * @package Amadeus\Client\Struct\Fare\CheckRules
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FareCategories
{
    /**
     * Possible values:
     *
     * 700 Normal fares
     * 701 Special Fares
     * 702 APEX Fares
     * 703 Super APEX Fares
     * 704 APEX and Super APEX Fares
     * 705 Round the World Fare
     * 706 Circle Trip/Triangle Fares
     * 707 Excursion Fares
     * 708 PEX Fares
     * 709 Super PEX Fares
     * 710 PEX and APEX Fares
     * 711 PEX and Super PEX Fares
     * 712 Eurobudget fares
     * 713 Visit USA fares
     * 714 Military fares
     * 715 All Fares
     * 716 Discount Fares
     * 717 First Class travel fares
     * 718 Intermediate class travel fares
     * 719 Economy class travel fares
     * 720 Unsaleable Fares
     * 721 Display Only Fare (fare cannot be used for pricing)
     * 722 Government ordered fares
     * 723 Group and Inclusive tour fares
     * 724 Inclusive tour fares
     * 725 Round the world and circle trip and/or triangle fares
     * 726 Common fares in which specified airlines participate
     *
     * @var string
     */
    public $fareType;

    /**
     * Possible values:
     *
     * 700 Normal fares
     * 701 Special Fares
     * 702 APEX Fares
     * 703 Super APEX Fares
     * 704 APEX and Super APEX Fares
     * 705 Round the World Fare
     * 706 Circle Trip/Triangle Fares
     * 707 Excursion Fares
     * 708 PEX Fares
     * 709 Super PEX Fares
     * 710 PEX and APEX Fares
     * 711 PEX and Super PEX Fares
     * 712 Eurobudget fares
     * 713 Visit USA fares
     * 714 Military fares
     * 715 All Fares
     * 716 Discount Fares
     * 717 First Class travel fares
     * 718 Intermediate class travel fares
     * 719 Economy class travel fares
     * 720 Unsaleable Fares
     * 721 Display Only Fare (fare cannot be used for pricing)
     * 722 Government ordered fares
     * 723 Group and Inclusive tour fares
     * 724 Inclusive tour fares
     * 725 Round the world and circle trip and/or triangle fares
     * 726 Common fares in which specified airlines participate
     *
     * @var string[]
     */
    public $otherFareType = [];
}
