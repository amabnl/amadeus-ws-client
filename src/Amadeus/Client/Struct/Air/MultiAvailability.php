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

namespace Amadeus\Client\Struct\Air;

use Amadeus\Client\RequestOptions\Air\MultiAvailability\FrequentTraveller as FrequentTravellerOptions;
use Amadeus\Client\RequestOptions\AirMultiAvailabilityOptions;
use Amadeus\Client\Struct\Air\MultiAvailability\ConsumerReferenceInformation;
use Amadeus\Client\Struct\Air\MultiAvailability\FrequentTraveller;
use Amadeus\Client\Struct\Air\MultiAvailability\PointOfCommencement;
use Amadeus\Client\Struct\Air\MultiAvailability\RequestSection;
use Amadeus\Client\Struct\Air\MultiAvailability\RequestSection16;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * Air_MultiAvailability version 15 and below
 *
 * @package Amadeus\Client\Struct\Air
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class MultiAvailability extends BaseWsMessage
{
    /**
     * @var MultiAvailability\MessageActionDetails
     */
    public $messageActionDetails;
    /**
     * @var MultiAvailability\ConsumerReferenceInformation
     */
    public $consumerReferenceInformation;
    /**
     * @var MultiAvailability\PointOfCommencement
     */
    public $pointOfCommencement;
    /**
     * @var MultiAvailability\FrequentTraveller
     */
    public $frequentTraveller;
    /**
     * @var MultiAvailability\RequestSection[]
     */
    public $requestSection = [];

    /**
     * MultiAvailability constructor.
     *
     * @param AirMultiAvailabilityOptions $params
     */
    public function __construct(AirMultiAvailabilityOptions $params)
    {
        $this->messageActionDetails = new MultiAvailability\MessageActionDetails(
            $params->actionCode,
            $params->businessFunction
        );

        if (count($params->requestOptions) > 0) {
            foreach ($params->requestOptions as $requestOption) {
                if ($this instanceof MultiAvailability16) {
                    $this->requestSection[] = new RequestSection16($requestOption);
                } else {
                    $this->requestSection[] = new RequestSection($requestOption);
                }
            }
        }

        $this->loadOptionalParams($params);
    }

    /**
     * @param AirMultiAvailabilityOptions $params
     */
    protected function loadOptionalParams($params)
    {
        if (!empty($params->commencementPoint)) {
            $this->pointOfCommencement = new PointOfCommencement(
                $params->commencementPoint,
                $params->commencementDate
            );
        }

        if (!empty($params->corporationNumber)) {
            $this->consumerReferenceInformation = new ConsumerReferenceInformation(
                $params->corporationNumber
            );
        }

        if ($params->frequentTraveller instanceof FrequentTravellerOptions) {
            $this->frequentTraveller = new FrequentTraveller($params->frequentTraveller);
        }
    }
}
