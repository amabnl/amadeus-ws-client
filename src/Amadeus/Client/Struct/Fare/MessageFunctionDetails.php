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

namespace Amadeus\Client\Struct\Fare;

/**
 * MessageFunctionDetails
 *
 * @package Amadeus\Client\Struct\Fare
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MessageFunctionDetails
{
    /**
     * Message function code for 'Fare Display by city pair'
     *
     * See functional documentation of Fare_DisplayFaresForCityPair
     * https://webservices.amadeus.com/extranet/structures/
     * viewMessageStructure.do?id=21&serviceVersionId=11#_42590_D_APA_Fare_10_codeset
     *
     * @var int
     */
    const FARE_DISPLAY_BY_CITY_PAIR = 711;
    /**
     * Message function code for 'Fare Check Rules'
     *
     * See functional documentation of Fare_CheckRules
     * https://webservices.amadeus.com/extranet/structures/
     * viewMessageStructure.do?id=742&serviceVersionId=719#_244505_D_APA_Fare_82_codeset
     *
     * @var int
     */
    const FARE_DISPLAY_RULES = 712;
    /**
     * Message function code for 'Fare Reservation Booking (Code) Designator Display'
     *
     * See functional documentation of Fare_DisplayBookingCodeInformation
     * https://webservices.amadeus.com/extranet/structures/
     * viewMessageStructure.do?id=1487&serviceVersionId=1697#_339248_D_APA_Fare_92_codeset
     *
     * @var int
     */
    const FARE_DISPLAY_BOOKING_CODE_INFORMATION = 724;
    /**
     * Message function code for 'Fare Currency conversion'
     *
     * See functional documentation of Fare_ConvertCurrency
     * https://webservices.amadeus.com/extranet/structures/
     * viewMessageStructure.do?id=1050&serviceVersionId=1249#_333441_D_APA_Fare_86_codeset
     *
     * @var int
     */
    const FARE_CURRENCY_CONVERSION = 726;
    /**
     * Message function code for 'Fare CalculateMileage'
     *
     * See functional documentation of Fare_ConvertCurrency
     * https://webservices.amadeus.com/extranet/structures/
     * viewMessageStructure.do?id=1050&serviceVersionId=1249#_333441_D_APA_Fare_86_codeset
     *
     * @var int
     */
    const FARE_CALCULATE_MILEAGE = 717;

    /**
     * Message function code for 'Fare Get Fare Rules'
     *
     * See functional documentation of Fare_GetFareRules
     *
     * @var string
     */
    const FARE_GET_FARE_RULES = "FRN";

    /**
     * @var string|int
     */
    public $messageFunction;

    /**
     * MessageFunctionDetails constructor.
     *
     * @param string|int|null $messageFunction
     */
    public function __construct($messageFunction = null)
    {
        $this->messageFunction = $messageFunction;
    }
}
