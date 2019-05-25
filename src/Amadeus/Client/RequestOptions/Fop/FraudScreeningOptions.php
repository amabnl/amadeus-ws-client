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

namespace Amadeus\Client\RequestOptions\Fop;

use Amadeus\Client\LoadParamsFromArray;

/**
 * Fraud Screening Options for Credit Card payments
 *
 * @package Amadeus\Client\RequestOptions\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FraudScreeningOptions extends LoadParamsFromArray
{
    const ID_DOC_CPF__BRAZILIAN_SECURITY_NUMBER = "CP";
    const ID_DOC_DRIVER_LICENSE = "DLN";
    const ID_DOC_FREQUENT_FLYER_NUMBER = "FFN";
    const ID_DOC_LOCALLY_DEFINED_NUMBER = "ID";
    const ID_DOC_NATIONAL_IDENTITY_CARD_NUMBER = "NI";
    const ID_DOC_PASSEPORT_NUMBER = "PP";
    const ID_DOC_SOCIAL_SECURITY_NUMBER = "SSN";

    /**
     * Perform fraud screening?
     *
     * @var bool
     */
    public $doFraudScreening = true;

    /**
     * @var string
     */
    public $ipAddress;

    /**
     * @var string
     */
    public $merchantUrl;

    /**
     * CC holder first name
     *
     * @var string
     */
    public $firstName;

    /**
     * CC holder last name
     *
     * @var string
     */
    public $lastName;

    /**
     * Payer's Date of Birth
     *
     * @var \DateTime
     */
    public $dateOfBirth;

    /**
     * Identity document number
     *
     * @var string
     */
    public $idDocumentNr;

    /**
     * Type of Identity document
     *
     * self::ID_DOC_*
     *
     * @var string
     */
    public $idDocumentType;

    /**
     * @var FraudScreeningAddress
     */
    public $billingAddress;

    /**
     * Payer telephone number
     *
     * @var string
     */
    public $phone;

    /**
     * Payer email address
     *
     * @var string
     */
    public $email;
}
