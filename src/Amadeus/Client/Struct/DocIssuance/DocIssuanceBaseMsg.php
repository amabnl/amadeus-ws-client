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

use Amadeus\Client\RequestOptions\DocIssuance\CompoundOption;
use Amadeus\Client\RequestOptions\DocIssuance\Option;
use Amadeus\Client\Struct\BaseWsMessage;

/**
 * DocIssuanceBaseMsg
 *
 * Basic Request structure for the DocIssuance messages
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class DocIssuanceBaseMsg extends BaseWsMessage
{
    /**
     * @var Selection
     */
    public $selection;

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
     * @param string[]|Option[] $options
     * @param CompoundOption[] $compoundOptions
     */
    protected function loadOptions($options, $compoundOptions)
    {
        foreach ($options as $option) {
            $this->optionGroup[] = new OptionGroup($option);
        }

        foreach ($compoundOptions as $compoundOption) {
            $this->otherCompoundOptions[] = new OtherCompoundOptions(
                $compoundOption->type,
                $compoundOption->details
            );
        }
    }

    /**
     * @param ReferenceDetails $ref
     */
    protected function addSelectionItem(ReferenceDetails $ref)
    {
        if (is_null($this->selection) || empty($this->selection)) {
            $this->selection = new Selection();
        }

        $this->selection->referenceDetails[] = $ref;
    }

    /**
     * @param string|null $passType
     */
    protected function loadPassType($passType)
    {
        if (!empty($passType)) {
            $this->infantOrAdultAssociation = new InfantOrAdultAssociation($passType);
        }
    }
}
