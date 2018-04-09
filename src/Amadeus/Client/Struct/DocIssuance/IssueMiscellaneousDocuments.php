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

use Amadeus\Client\RequestOptions\DocIssuanceIssueMiscDocOptions;

/**
 * DocIssuance_IssueMiscellaneousDocuments request structure
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class IssueMiscellaneousDocuments extends DocIssuanceBaseMsg
{
    /**
     * IssueMiscellaneousDocuments constructor.
     *
     * @param DocIssuanceIssueMiscDocOptions $options
     */
    public function __construct(DocIssuanceIssueMiscDocOptions $options)
    {
        $this->addSelectionItems($options->tsmNumbers, ReferenceDetails::TYPE_TSM);
        $this->addSelectionItems($options->tsmTattoos, ReferenceDetails::TYPE_TSM_TATTOO);
        $this->addSelectionItems($options->lineNumbers, ReferenceDetails::TYPE_LINE_NUMBER);

        $this->loadOptions($options->options, $options->compoundOptions);

        $this->loadReferences($options);

        $this->loadPassType($options->passengerType);

        $this->loadStockReference($options->stockReference);
    }

    /**
     * @param string[]|int[] $list
     * @param string $type
     */
    protected function addSelectionItems($list, $type)
    {
        foreach ($list as $item) {
            $this->addSelectionItem(
                new ReferenceDetails(
                    $item,
                    $type
                )
            );
        }
    }

    /**
     * @param DocIssuanceIssueMiscDocOptions $options
     */
    protected function loadReferences($options)
    {
        foreach ($options->passengerNumbers as $paxNum) {
            $this->paxSelection[] = new PaxSelection(
                $paxNum,
                PassengerReference::TYPE_PAX_SELECTION
            );
        }

        foreach ($options->passengerTattoos as $paxTat) {
            $this->paxSelection[] = new PaxSelection(
                $paxTat,
                PassengerReference::TYPE_PAX_TATTOO
            );
        }
    }

    /**
     * @param string|null $stockRef
     */
    protected function loadStockReference($stockRef)
    {
        if (!is_null($stockRef)) {
            $this->stock = new Stock($stockRef);
        }
    }
}
