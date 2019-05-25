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

use Amadeus\Client\RequestOptions\Fop\FraudScreeningOptions;
use Amadeus\Client\Struct\WsMessageUtility;

/**
 * FraudScreeningData
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class FraudScreeningData extends WsMessageUtility
{
    /**
     * @var FraudScreening
     */
    public $fraudScreening;

    /**
     * @var IpAdress
     */
    public $ipAdress;

    /**
     * @var MerchantUrl
     */
    public $merchantURL;

    /**
     * @var PayerPhoneOrEmail[]
     */
    public $payerPhoneOrEmail = [];

    /**
     * @var ShopperSession
     */
    public $shopperSession;

    /**
     * @var PayerName
     */
    public $payerName;

    /**
     * @var PayerDateOfBirth
     */
    public $payerDateOfBirth;

    /**
     * @var BillingAddress
     */
    public $billingAddress;

    /**
     * @var FormOfIdDetails[]
     */
    public $formOfIdDetails = [];

    /**
     * @var TravelShopper
     */
    public $travelShopper;

    /**
     * @var ShopperDetails
     */
    public $shopperDetails;

    /**
     * @var SecurityCode[]
     */
    public $securityCode = [];

    /**
     * FraudScreeningData constructor.
     *
     * @param FraudScreeningOptions $options
     */
    public function __construct(FraudScreeningOptions $options)
    {
        $this->fraudScreening = new FraudScreening($options->doFraudScreening);

        if (!empty($options->ipAddress)) {
            $this->ipAdress = new IpAdress($options->ipAddress);
        }

        if (!empty($options->phone)) {
            $this->payerPhoneOrEmail[] = new PayerPhoneOrEmail(PayerPhoneOrEmail::TYPE_PHONE, $options->phone);
        }

        if (!empty($options->email)) {
            $this->payerPhoneOrEmail[] = new PayerPhoneOrEmail(PayerPhoneOrEmail::TYPE_EMAIL, $options->email);
        }

        if ($this->checkAnyNotEmpty($options->firstName, $options->lastName)) {
            $this->payerName = new PayerName($options->lastName, $options->firstName);
        }

        if ($options->dateOfBirth instanceof \DateTime) {
            $this->payerDateOfBirth = new PayerDateOfBirth($options->dateOfBirth);
        }

        if ($this->checkAnyNotEmpty($options->idDocumentNr, $options->idDocumentType)) {
            $this->formOfIdDetails[] = new FormOfIdDetails($options->idDocumentNr, $options->idDocumentType);
        }

        if (!empty($options->billingAddress)) {
            $this->billingAddress = new BillingAddress(
                $options->billingAddress->addressLines,
                $options->billingAddress->city,
                $options->billingAddress->zipCode,
                $options->billingAddress->countryCode
            );
        }
    }
}
