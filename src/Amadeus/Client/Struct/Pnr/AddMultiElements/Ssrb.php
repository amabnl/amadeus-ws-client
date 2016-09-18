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

namespace Amadeus\Client\Struct\Pnr\AddMultiElements;

/**
 * Ssrb
 *
 * @package Amadeus\Client\Struct\Pnr\AddMultiElements
 */
class Ssrb
{
    /**
     * To specify additional information relating to the special requirement type.
     *
     * @var string
     */
    public $data;

    /**
     * Seat characteristic
     *
     * A Aisle seat
     * H Seat with facilities for Handicapped/incappacited
     * I Seat suitable for adult with an infant
     * K Bulkhead seat (B in custumer profile)
     * MA Medicaly OK for travel
     * N No smoking seat
     * S Smoking seat
     * U Seat suitable for unaccompanied minor
     * W Window seat
     *
     * @var string
     */
    public $seatType;
}
