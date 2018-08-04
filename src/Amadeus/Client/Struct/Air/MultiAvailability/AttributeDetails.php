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

namespace Amadeus\Client\Struct\Air\MultiAvailability;

/**
 * AttributeDetails
 *
 * @package Amadeus\Client\Struct\Air\MultiAvailability
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class AttributeDetails
{
    const TYPE_AVLFORGROUPCAPPED_EX = "AGC";
    const TYPE_AVAILABILITYCAP_EX = "AVC";
    const TYPE_AWARD_PROGRAM = "AWP";
    const TYPE_AWARD_ZONE = "AWZ";
    const TYPE_BATCHAVSREFRESH_EX = "BAR";
    const TYPE_BIDPRICEAVLTHRESHOLD_AIRL = "BAT";
    const TYPE_BATCHAVS_EX = "BAV";
    const TYPE_BOARD_POINT = "BOA";
    const TYPE_BOARD_CODE_CONTEXT = "BPR";
    const TYPE_BIDPRICEPRORATIONTOLERANCE_AIRL = "BPT";
    const TYPE_CABINS_ORDER_AIRL = "CAO";
    const TYPE_CARRIERCURRENCYCODE_AIRL = "CCC";
    const TYPE_CROSSCABINGLOBALSWITCH_AIRL = "CCS";
    const TYPE_CLASSES_ORDER_AIRL = "CLO";
    const TYPE_CONFIRMPGAVLOK_EX = "CPG";
    const TYPE_CONFIRMSGAVLOK_EX = "CSG";
    const TYPE_DBI_INFO_CORP_CREDIT_CARD = "DBI";
    const TYPE_SECTOR_DISTANCE = "DIS";
    const TYPE_EOTACCEPTML_EX = "EML";
    const TYPE_MAX_ELAPSED_TIME = "ETT";
    const TYPE_EFFECTIVEYIELDMTHD_AIRL = "EYM";
    const TYPE_FLIGHTCONNECTIONTIMELIMIT_AIRL = "FCT";
    const TYPE_INTERPOLATEBETWEENBUCKETS_AIRL = "IB";
    const TYPE_INCREMENTAL_WAITING_TIME = "ILT";
    const TYPE_LOCATION_EX = "LOC";
    const TYPE_MAX_CONNECTING_TIME = "MAX";
    const TYPE_MLENFORCMENTTIMELIMIT_EX = "MET";
    const TYPE_MIN_CONNECTING_TIME = "MIN";
    const TYPE_MAXLINES_EX = "ML";
    const TYPE_MLENFORCED_EX = "MLE";
    const TYPE_SETMLWITHTTY_EX = "MLT";
    const TYPE_NO = "N";
    const TYPE_NAVSFORMAT_EX = "NAF";
    const TYPE_ROMANIZABLE_NATIVE_NAME = "NN1";
    const TYPE_NONROMANIZABLE_NATIVE_NAME = "NN2";
    const TYPE_NO_FARE_FOR_OFFER = "NOF";
    const TYPE_MAX_NR_OF_SERVICES_CHANGES = "NSC";
    const TYPE_MAX_NR_OF_TRANPORT_TYPE_CHANGES = "NTC";
    const TYPE_ONDAVAILABILITYGLOBALSWITCH_AIRL = "OAS";
    const TYPE_OFFER_ACTIVE = "OFA";
    const TYPE_OFFER_EXPIRED = "OFE";
    const TYPE_OFF_POINT = "OFF";
    const TYPE_OFFER_CONFIRMED = "OFK";
    const TYPE_OFFER_PARTIALLY_CONFIRMED = "OFP";
    const TYPE_OFFER_UNAVAILABLE = "OFU";
    const TYPE_OFF_CODE_CONTEXT = "OPR";
    const TYPE_PAYMENT = "PAY";
    const TYPE_PACKAGE_NAME = "PKG";
    const TYPE_PROTECTFOROVERSALE_AIRL = "PO";
    const TYPE_REVENUECONTROLBIDPRICEMETHOD_AIRL = "RBP";
    const TYPE_REVENUECONTROLGLOBALSWITCH_AIRL = "RCS";
    const TYPE_ROMANIZATION_NATIVE_ASCII = "RN1";
    const TYPE_ROMANIZATION_NATIVE_EXT_ASCII = "RN2";
    const TYPE_RETURN_ONE_WAY = "ROW";
    const TYPE_REJECTSSATEOT_AIRL = "RSE";
    const TYPE_REJECTSSALLOWEDHOURS_AIRL = "RSH";
    const TYPE_SENDAVSPERSEGMENT_EX = "SAS";
    const TYPE_SELECTIVEPOLLINGFORMAT_EX = "SPF";
    const TYPE_RETURN_INTERMEDIATE_STOP_TAG = "STO";
    const TYPE_SENDWAITLISTCLOSED_EX = "SWC";
    const TYPE_TTYADDRESS_EX_AIRL = "TAD";
    const TYPE_TIMINGCHANGELOWERVALUE_AIRL = "TLV";
    const TYPE_TIMINGCHANGEMCTFACTOR_AIRL = "TMF";
    const TYPE_TURNAROUND_POINT = "TPT";
    const TYPE_TRAIN_TYPE = "TRA";
    const TYPE_TRANSPORT_TYPE = "TRT";
    const TYPE_TIMINGCHANGEUPPERVALUE_AIRL = "TUV";
    const TYPE_TYPE_EX = "TYP";
    const TYPE_UNIVERSAL_NAME = "UN";
    const TYPE_YES = "Y";

    /**
     * @var string
     */
    public $attributeType;

    /**
     * @var string
     */
    public $attributeDescription;

    /**
     * AttributeDetails constructor.
     *
     * @param string $attributeType
     * @param string $attributeDescription
     */
    public function __construct($attributeType, $attributeDescription)
    {
        $this->attributeType = $attributeType;
        $this->attributeDescription = $attributeDescription;
    }
}
