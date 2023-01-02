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

namespace Amadeus\Client\RequestOptions;

/**
 * Amadeus Queue - definition of a GDS Queue (Queue, Category and optional Office ID)
 *
 * @package Amadeus
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Queue
{
    const QUEUE_GENERAL = 0;
    const QUEUE_CONFIRMATION = 1;
    const QUEUE_CONFIRMATION_WAITLIST = 2;
    const QUEUE_OPTION = 3;
    const QUEUE_RESPONSIBLE_OFFICE_CHANGE = 4;
    const QUEUE_SCHEDULE_CHANGE = 7;
    const QUEUE_TICKETING = 8;
    const QUEUE_OTHER_AIRLINE_CONTROL = 9;
    const QUEUE_EXPIRED_TIME_LIMIT = 12;
    const QUEUE_REQUEST_FOR_REPLY = 23;
    const QUEUE_PREPAID_TICKET_ADVICE = 80;
    const QUEUE_GROUPS = 87;
    const QUEUE_MESSAGE_CUSTOMER_PROFILE = 94;
    const QUEUE_MESSAGE = 97;

    /**
     * self::QUEUE_*
     *
     * @var int
     */
    public $queue;

    /**
     * OPTIONAL
     *
     * @var int
     */
    public $category = 0;

    /**
     * OPTIONAL
     *
     * @var string
     */
    public $officeId;
    
    /**
     * OPTIONAL
     *
     * @var string
     */
    public $timeMode;

    /**
     * @var string
     */
    public $accountNumber;
    
    /**
     * Construct Queue with initialization array
     *
     * @param array $params Initialization parameters
     */
    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
            }
        }
    }
}
