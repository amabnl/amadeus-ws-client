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

namespace Amadeus\Client\RequestOptions\Pnr\Element;

use Amadeus\Client\RequestOptions\Pnr\Element;

/**
 * Contact element - AP element
 *
 * @package Amadeus\Client\RequestOptions\Pnr\Element
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Contact extends Element
{
    const TYPE_PHONE_GENERAL = "5";

    const TYPE_PHONE_HOME = "4";

    const TYPE_PHONE_BUSINESS = "3";

    const TYPE_PHONE_MOBILE = "7";

    const TYPE_FAX = "P01";

    const TYPE_EMAIL = "P02";

    const TYPE_INTERNETADDRESS = "P03";


    /**
     * The type of contact info. See self::TYPE_*
     *
     * PNR_AddMultiElements/dataElementsMaster/dataElementsIndiv/freetextData/freetextDetail/type
     *
     * 10 Endorsement information
     * 11 Commission information
     * 12 Tour number
     * 16 Form of payment information
     * 17 Ticketing information
     * 2 Address (home or hotel)
     * 28 Other service information (OSI)
     * 3 Business phone
     * 4 Home phone number
     * 45 Original issue information
     * 5 Telephone nature not known
     * 6 Travel agent telephone number
     * 60 Manual Priority Line
     * 7 Mobile Phone Number
     * N Notification element
     * P01 Fax number
     * P02 E-mail address
     * P03 Intenet address
     * P04 Fare discount
     * P05 Fare print override
     * P06 Automated ticket
     * P07 AIR sequence number
     * P08 Mailing address
     * P09 Address verification
     * P10 AK-train pending information
     * P11 Temporary TAG line of the PNR header
     * P12 TAG line of the PNR header
     * P13 Retrieve options of the PNR header
     * P14 Shadow AIR sequence number
     * P15 Manual document
     * P16 Automated invoice number
     * P17 Control number
     * P18 Ticketing carrier
     * P19 Miscellaneous information
     * P20 Accounting information
     * P21 Option information
     * P22 Receive from
     * P23 Shadow destination
     * P24 Home address for mailing address
     * P25 Delivery address for mailing address
     * P26 For tour operator name
     * P27 For OS element
     * P28 For other special information element
     * P30 For ship name - Cruise distribution data
     *
     * @var string
     */
    public $type;

    /**
     * The contact information to be added
     *
     * @var string
     */
    public $value;
}
