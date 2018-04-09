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

namespace Amadeus\Client\ResponseHandler\Air;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * Utility class to get proper error messages from an Air_RetrieveSeatMapReply message
 *
 * @package Amadeus\Client\ResponseHandler\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class HandlerRetrieveSeatMap extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        $analyzeResponse = new Result($response);

        $domXpath = $this->makeDomXpath($response->responseXml);

        $errorCodeNode = $domXpath->query('//m:errorInformation/m:errorDetails/m:code');
        if ($errorCodeNode->length > 0) {
            $analyzeResponse->status = Result::STATUS_ERROR;

            $errCode = $errorCodeNode->item(0)->nodeValue;
            $level = null;

            $errorLevelNode = $domXpath->query('//m:errorInformation/m:errorDetails/m:processingLevel');
            if ($errorLevelNode->length > 0) {
                $level = self::decodeProcessingLevel($errorLevelNode->item(0)->nodeValue);
            }

            $errorDescNode = $domXpath->query('//m:errorInformation/m:errorDetails/m:description');
            if ($errorDescNode->length > 0) {
                $errDesc = $errorDescNode->item(0)->nodeValue;
            } else {
                $errDesc = self::findMessage($errCode);
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $errCode,
                $errDesc,
                $level
            );
        }

        $codeNode = $domXpath->query('//m:warningInformation/m:warningDetails/m:number');
        if ($codeNode->length > 0) {
            $analyzeResponse->status = Result::STATUS_WARN;

            $warnCode = $codeNode->item(0)->nodeValue;
            $level = null;

            $levelNode = $domXpath->query('//m:warningInformation/m:warningDetails/m:processingLevel');
            if ($levelNode->length > 0) {
                $level = self::decodeProcessingLevel($levelNode->item(0)->nodeValue);
            }

            $descNode = $domXpath->query('//m:warningInformation/m:warningDetails/m:description');
            if ($descNode->length > 0) {
                $warnDesc = $descNode->item(0)->nodeValue;
            } else {
                $warnDesc = self::findMessage($warnCode);
            }

            $analyzeResponse->messages[] = new Result\NotOk(
                $warnCode,
                $warnDesc,
                $level
            );
        }

        return $analyzeResponse;
    }

    /**
     * Find the error message for a given error code
     *
     * @param string $code
     * @return string|null
     */
    public static function findMessage($code)
    {
        $message = null;

        if (array_key_exists($code, self::$errorList)) {
            $message = self::$errorList[$code];
        }

        return $message;
    }

    /**
     * Decode error processing level code
     *
     * @param int|string $level
     * @return string|null
     */
    public static function decodeProcessingLevel($level)
    {
        $decoded = null;

        $map = [
            0 => 'system',
            1 => 'application'
        ];

        if (array_key_exists($level, $map)) {
            $decoded = $map[$level];
        }

        return $decoded;
    }

    /**
     * @var array
     */
    public static $errorList = [
        '1' => 'Passenger surname not found',
        '10' => 'Location of arrival is invalid',
        '100' => 'Seat map not available, request seat at check-in',
        '101' => 'Seat map contains conditional seats, it may be subject to reseating',
        '102' => 'Unable to process',
        '103' => 'Segment/Passenger does not qualify for advanced seating',
        '11' => 'Departure/Arrival city pair is invalid',
        '12' => 'Unique name not found',
        '13' => 'Invalid seat number',
        '14' => 'Airline code and/or flight number invalid',
        '15' => 'Flight cancelled',
        '16' => 'Flight check-in held or suspended temporarily',
        '17' => 'Passenger surname already checked in',
        '18' => 'Seating conflict - request contradicts the facility rules',
        '185' => 'Use airline name',
        '186' => 'Use passenger status',
        '187' => 'Flight changes from smoking to non smoking',
        '188' => 'Flight changes from non smoking to smoking',
        '189' => 'Pax has pre-reserved exit seat unable to C/I',
        '19' => 'Baggage weight is required',
        '190' => 'Pax cannot be seated together',
        '191' => 'Generic seat change not supported',
        '192' => 'Seat change-request in row change not supported',
        '193' => 'API pax data required',
        '194' => 'Passenger surname not checked in',
        '195' => 'Change of equipment on this flight',
        '196' => 'Time out occured on host 3',
        '197' => 'Error in frequent flyer number',
        '198' => 'Class code required',
        '199' => 'Check-in separately',
        '2' => 'Seat not available on the requested class/zone',
        '20' => 'Bag count conflict - weight update for non-existing bag',
        '200' => 'FQTV number not accepted',
        '201' => 'FQTV number already present',
        '202' => 'Baggage details not updated',
        '203' => 'SSR details not updated',
        '204' => 'Row invalid',
        '205' => 'Short connection baggage',
        '206' => 'Seat change only supported for single passenger',
        '207' => 'Use generic seating only',
        '208' => 'Update separately',
        '209' => 'Flight changes from seating to openseating (freeseating)',
        '21' => 'Seats not available for passenger type',
        '210' => 'Flight changes from openseating (freeseating) to seating',
        '211' => 'Unable to through-check - complexing/COG/codeshare flight',
        '212' => 'API pax data not supported',
        '213' => 'Time invalid - max/min connecting time for though checkin',
        '214' => 'API date of birth required',
        '215' => 'API passport number required',
        '217' => 'API pax first name required',
        '218' => 'API pax gender required',
        '22' => 'Too many connections - need manual tags',
        '223' => 'API infant data required',
        '224' => 'Passenger holds advance boarding pass',
        '225' => 'Seat Map not available as flight operated by another carrier',
        '226' => 'Seat Request not available as flight operated by another carrier',
        '227' => 'Seat change not possible/seats limited in this area',
        '228' => 'Exchange advanced boarding pass - new data',
        '229' => 'Change not performed on subsequent flight(s)',
        '23' => 'Invalid bag destination - need manual tags',
        '230' => 'Unable to seat change - complexing/COG/codeshare flight',
        '231' => 'Passenger not E.T. type',
        '232' => 'Passenger is E.T. type - needs E.T. indicator',
        '233' => 'Passenger is E.T. type - needs E.T. number',
        '234' => 'Unable to through check - E.T. passenger',
        '235' => 'Tier level not accepted',
        '236' => 'Unable to process - codeshare flight',
        '237' => 'Forward seat due connection - no seat change allowed',
        '238' => 'Australian Visa required - use TIETAC entry each pax',
        '239' => 'Advice of handcarry baggage allowance',
        '24' => 'Passenger actual weight required for this flight',
        '240' => 'Emergency contact information required for U.S. citizen',
        '244' => 'Epass passenger - number differs',
        '245' => 'Epass passenger - not authorized',
        '246' => 'Epass passenger - possible status discrepency',
        '247' => 'Message limit exceed',
        '248' => 'Insufficient upgrade balance',
        '249' => 'Reissue bag tags to correct destination',
        '25' => 'Hand baggage details required',
        '250' => 'Add API data - retry Check in',
        '251' => 'Advise visa and/or documentation status',
        '252' => 'Passenger is E.T. type - ticket/reservation conflict',
        '253' => 'Passenger is E.T. type - ticket record not accessible',
        '254' => 'Cascaded query timed out',
        '255' => 'Unable to check-in - Security profile restriction',
        '256' => 'Passport number printed on boarding pass required',
        '26' => 'No seat selection on this flight',
        '264' => 'API pax data not required',
        '265' => 'Missing or invalid airport check-in identification (FOID)',
        '27' => 'Location of departure is invalid',
        '28' => 'Flight rescheduled - through check-in no longer allowed',
        '29' => 'Flight full in the requested class',
        '3' => 'Invalid seat request',
        '30' => 'Passenger surname off-loaded',
        '300' => 'Seat Map not available for unticketed passengers',
        '31' => 'Passenger surname deleted/cancelled from the flight',
        '32' => 'Bag tag number invalid',
        '33' => 'Flight gated - through check-in is not allowed',
        '34' => 'Time invalid - minimum connecting time for check-in violated',
        '35' => 'Flight closed',
        '36' => 'Passenger not accessible in the system (error/protection)',
        '37' => 'Unique reference for passenger is invalid',
        '38' => 'Passenger party reference is invalid',
        '39' => 'Booking/Ticketing class conflict',
        '4' => 'Bag tag number details required',
        '40' => 'Status conflict - status does not exist',
        '41' => 'Frequent flyer number is invalid',
        '42' => 'Booking/Ticketing class invalid',
        '43' => 'Passenger type conflicts with seats held',
        '44' => 'Too many passengers',
        '45' => 'Unable - group names',
        '46' => 'Unable to check-in partial party',
        '47' => 'Passenger status conflict',
        '48' => 'PNR locator unknown in the receiving system',
        '49' => 'Ticket number invalid',
        '5' => 'Invalid flight/Date',
        '50' => 'Pool airline invalid',
        '51' => 'Operating airline invalid',
        '52' => 'Not authorized - company level',
        '53' => 'Not authorized - station level',
        '54' => 'Not authorized - data level',
        '55' => 'Passenger regraded',
        '56' => 'Passenger seated elsewhere than requested',
        '57' => 'Seat not available in the requested class',
        '58' => 'Seat not available in the requested zone',
        '59' => 'Specific seat not available',
        '6' => 'Too many passengers with the same Surname',
        '60' => 'Free seating in the requested flight',
        '61' => 'Too many infants',
        '62' => 'Smoking zone unavailable',
        '63' => 'Non-smoking zone unavailable',
        '64' => 'Indifferent zone unavailable',
        '65' => 'Check visa and/or documentation',
        '66' => 'No baggage update required for this flight',
        '67' => 'Gender weight is required',
        '68' => 'Item conflict',
        '69' => 'Item weight is required',
        '7' => 'Passenger type or gender conflict',
        '70' => 'Modification not possible',
        '700' => 'Item/data not found - data not existing in processing host',
        '701' => 'Invalid format - data does not match EDIFACT rules',
        '702' => 'No action - Processing host can not support the function',
        '71' => 'No common itinerary',
        '72' => 'Unable to give seat',
        '73' => 'Passenger needs initial',
        '74' => 'Passenger needs first name',
        '75' => 'Collect second flight name',
        '76' => 'Check smallpox vaccination',
        '77' => 'Check yellow fever vaccination',
        '78' => 'Check cholera vaccination',
        '79' => 'Passenger has pre-reserved seat',
        '8' => 'More precise gender is required',
        '80' => 'Flight initialised - retry check in',
        '81' => 'Bag through labeling not allowed beyond this station',
        '82' => 'Too many bags',
        '83' => 'Flight operated as',
        '84' => 'Function not supported',
        '85' => 'Invalid reservations booking modifier',
        '86' => 'Invalid compartment designator code',
        '87' => 'Invalid country code',
        '88' => 'Invalid source of business',
        '89' => 'Invalid agent\'s code',
        '9' => 'Flight is not open for through check-in',
        '90' => 'Requester identification required',
        '91' => 'Seat Map Display request is outside system date range',
        '92' => 'Flight does not operate due to weather, mechanical or other operational conditions',
        '93' => 'Flight does not operate on date requested',
        '94' => 'Flight does not operate between requested cities',
        '95' => 'Schedule change in progress',
        '96' => 'Repeat request updating in progress',
        '97' => 'Flight has departed',
        '98' => 'Seating closed due flight under departure control',
        '99' => 'Seat map not available for requested zone, seat may be requested',
    ];
}
