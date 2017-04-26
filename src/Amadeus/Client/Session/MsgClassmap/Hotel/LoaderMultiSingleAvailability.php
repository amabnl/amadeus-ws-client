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

namespace Amadeus\Client\Session\MsgClassmap\Hotel;

use Amadeus\Client\Session\MsgClassmap\LoadInterface;

/**
 * Hotel_MultiSingleAvailability Classmap loader
 *
 * @package Amadeus\Client\Session\MsgClassmap\Hotel
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class LoaderMultiSingleAvailability implements LoadInterface
{
    /**
     * @param float|string $version
     * @return array
     */
    public static function loadClassMapForMessage($version)
    {
        return [
            'ota_2003_05:OTA_HotelAvailRQ' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability',
            'ota_2003_05:AvailRequestSegments' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\AvailRequestSegments',
            'ota_2003_05:AvailRequestSegmentsType' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\AvailRequestSegmentsType',
            'ota_2003_05:AvailRequestSegment' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\AvailRequestSegment',
            'ota_2003_05:Criterion' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\Criterion',
            'ota_2003_05:HotelSearchCriterionType' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\HotelSearchCriterionType',
            'ota_2003_05:ItemSearchCriterionType' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\ItemSearchCriterionType',
            'ota_2003_05:GuestCount' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\GuestCount',
            'ota_2003_05:GuestCounts' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\GuestCounts',
            'ota_2003_05:HotelRef' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\HotelRef',
            'ota_2003_05:HotelSearchCriteria' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\HotelSearchCriteria',
            'ota_2003_05:HotelSearchCriteriaType' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\HotelSearchCriteriaType',
            'ota_2003_05:POS_Type' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\PosType',
            'ota_2003_05:RateRange' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\RateRange',
            'ota_2003_05:RoomStayCandidate' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\RoomStayCandidate',
            'ota_2003_05:RoomStayCandidates' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\RoomStayCandidates',
            'ota_2003_05:StayDateRange' => 'Amadeus\Client\Struct\Hotel\MultiSingleAvailability\StayDateRange'
        ];
    }
}
