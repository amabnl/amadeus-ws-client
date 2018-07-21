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

namespace Amadeus\Client\Struct\DocIssuance;

use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;

/**
 * DocIssuance_IssueTicket request structure
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IssueTicket extends DocIssuanceBaseMsg
{
    /**
     * @var AgentInfo
     */
    public $agentInfo;

    /**
     * @var OverrideDate
     */
    public $overrideDate;


    /**
     * IssueTicket constructor.
     *
     * @param DocIssuanceIssueTicketOptions $options
     */
    public function __construct(DocIssuanceIssueTicketOptions $options)
    {
        foreach ($options->tsts as $tst) {
            $this->addSelectionItem(
                new ReferenceDetails(
                    $tst,
                    ReferenceDetails::TYPE_TST
                )
            );
        }

        $this->loadOverrideDate($options);

        $this->loadReferences($options);

        $this->loadOptions($options->options, $options->compoundOptions);

        if (!empty($options->agentCode)) {
            $this->agentInfo = new AgentInfo($options->agentCode);
        }

        $this->loadPassType($options->passengerType);
    }

    /**
     * @param DocIssuanceIssueTicketOptions $options
     */
    protected function loadOverrideDate(DocIssuanceIssueTicketOptions $options)
    {
        if ($options->alternateDateValidation instanceof \DateTime) {
            $this->overrideDate = new OverrideDate(
                OverrideDate::OPT_ALTERNATE_DATE_VALIDATION,
                $options->alternateDateValidation
            );
        } elseif ($options->overridePastDateTst === true) {
            $this->overrideDate = new OverrideDate(OverrideDate::OPT_OVERRIDE_PAST_DATE_TST);
        }
    }

    /**
     * @param DocIssuanceIssueTicketOptions $options
     */
    protected function loadReferences(DocIssuanceIssueTicketOptions $options)
    {
        foreach ($options->passengerTattoos as $passengerTattoo) {
            $this->paxSelection[] = new PaxSelection($passengerTattoo);
        }

        foreach ($options->segmentTattoos as $segmentTattoo) {
            $this->addSelectionItem(
                new ReferenceDetails(
                    $segmentTattoo,
                    ReferenceDetails::TYPE_SEGMENT_TATTOO
                )
            );
        }

        foreach ($options->lineNumbers as $lineNumber) {
            $this->addSelectionItem(
                new ReferenceDetails(
                    $lineNumber,
                    ReferenceDetails::TYPE_LINE_NUMBER
                )
            );
        }

        foreach ($options->couponNumbers as $couponNumber) {
            $this->addSelectionItem(
                new ReferenceDetails(
                    $couponNumber,
                    ReferenceDetails::TYPE_COUPON_NUMBER
                )
            );
        }
    }
}
