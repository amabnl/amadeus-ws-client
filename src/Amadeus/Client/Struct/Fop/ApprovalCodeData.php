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

namespace Amadeus\Client\Struct\Fop;

/**
 * ApprovalCodeData
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ApprovalCodeData
{
    const APPROVAL_SOURCE_AUTOMATIC = "A";
    const APPROVAL_SOURCE_MANUAL_SETTLEMENT = "B";
    const APPROVAL_SOURCE_AUTOMATIC_SETTLEMENT = "F";
    const APPROVAL_SOURCE_AUTOMATIC_NON_AMADEUS_PAYMENT = "G";
    const APPROVAL_SOURCE_MANUAL = "M";

    /**
     * @var string
     */
    public $approvalCode;

    /**
     * self::APPROVAL_SOURCE_*
     *
     * @var string
     */
    public $sourceOfApproval;

    /**
     * ApprovalCodeData constructor.
     *
     * @param string $approvalCode
     * @param string $sourceOfApproval self::APPROVAL_SOURCE_*
     */
    public function __construct($approvalCode, $sourceOfApproval)
    {
        $this->approvalCode = $approvalCode;
        $this->sourceOfApproval = $sourceOfApproval;
    }
}
