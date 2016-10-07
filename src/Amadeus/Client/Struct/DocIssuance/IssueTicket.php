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
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * DocIssuance_IssueTicket
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dieter.devlieghere@benelux.amadeus.com>
 */
class IssueTicket extends BaseWsMessage
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
     * @var Selection[]
     */
    public $selection = [];

    /**
     * @var PaxSelection[]
     */
    public $paxSelection = [];

    /**
     * @var Stock
     */
    public $stock;

    /**
     * @var OptionGroup[]
     */
    public $optionGroup = [];

    /**
     * @var InfantOrAdultAssociation
     */
    public $infantOrAdultAssociation;

    /**
     * @var OtherCompoundOptions[]
     */
    public $otherCompoundOptions = [];

    /**
     * IssueTicket constructor.
     *
     * @param DocIssuanceIssueTicketOptions $options
     */
    public function __construct(DocIssuanceIssueTicketOptions $options)
    {
        if (!empty($options->tsts)) {
            foreach ($options->tsts as $tst) {
                $this->addSelectionItem(
                    new ReferenceDetails(
                        $tst,
                        ReferenceDetails::TYPE_TST
                    )
                );
            }
        }

        $this->loadOverrideDate($options);

        $this->loadReferences($options);

        $this->loadOptions($options);

        if (!empty($options->agentCode)) {
            $this->agentInfo = new AgentInfo($options->agentCode);
        }

        if (!empty($options->passengerType)) {
            $this->infantOrAdultAssociation = new InfantOrAdultAssociation($options->passengerType);
        }
    }

    /**
     * @param ReferenceDetails $ref
     */
    protected function addSelectionItem(ReferenceDetails $ref)
    {
        if (is_null($this->selection) || empty($this->selection)) {
            $this->selection[] = new Selection();
        }

        $this->selection[0]->referenceDetails[] = $ref;
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
        if (!empty($options->passengerTattoos)) {
            foreach ($options->passengerTattoos as $passengerTattoo) {
                $this->paxSelection[] = new PaxSelection($passengerTattoo);
            }
        }

        if (!empty($options->segmentTattoos)) {
            foreach ($options->segmentTattoos as $segmentTattoo) {
                $this->addSelectionItem(
                    new ReferenceDetails(
                        $segmentTattoo,
                        ReferenceDetails::TYPE_SEGMENT_TATTOO
                    )
                );
            }
        }
    }

    /**
     * @param DocIssuanceIssueTicketOptions $options
     */
    protected function loadOptions(DocIssuanceIssueTicketOptions $options)
    {
        if (!empty($options->options)) {
            foreach ($options->options as $option) {
                $this->optionGroup[] = new OptionGroup($option);
            }
        }

        if (!empty($options->compoundOptions)) {
            foreach ($options->compoundOptions as $compoundOption) {
                $this->otherCompoundOptions[] = new OtherCompoundOptions(
                    $compoundOption->type,
                    $compoundOption->details
                );
            }
        }
    }
}
