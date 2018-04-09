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

namespace Amadeus\Client\Struct\DocRefund\ProcessRefund;

/**
 * PhoneFaxEmailAddress
 *
 * @package Amadeus\Client\Struct\DocRefund\ProcessRefund
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class PhoneFaxEmailAddress
{
    const TYPE_E_MAIL_ADDRESS = "EML";
    const TYPE_E_MAIL_ADDRESS_FROM_APE = "EMLA";
    const TYPE_FAX_NUMBER = "FAX";
    const TYPE_FAX_NUMBER_FROM_APF = "FAXA";

    /**
     * self::TYPE
     *
     * @var string
     */
    public $phoneOrEmailType;

    /**
     * @var TelephoneNumberDetails
     */
    public $telephoneNumberDetails;

    /**
     * @var string
     */
    public $emailAddress;

    /**
     * PhoneFaxEmailAddress constructor.
     *
     * @param string $type
     * @param string|null $email
     * @param string|null $phoneNr
     * @param string|null $phoneArea
     */
    public function __construct($type, $email = null, $phoneNr = null, $phoneArea = null)
    {
        $this->phoneOrEmailType = $type;

        if ($type === self::TYPE_E_MAIL_ADDRESS) {
            $this->emailAddress = $email;
        }

        if ($type === self::TYPE_FAX_NUMBER) {
            $this->telephoneNumberDetails = new TelephoneNumberDetails($phoneNr, $phoneArea);
        }
    }
}
