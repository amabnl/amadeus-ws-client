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

namespace Amadeus\Client\Struct\Pnr\DisplayHistory;

use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\Predicate as PredicateOptions;
use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\PredicateDetail;
use Amadeus\Client\RequestOptions\Pnr\DisplayHistory\PredicateType;

/**
 * Predicate
 *
 * @package Amadeus\Client\Struct\Pnr\DisplayHistory
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class Predicate
{
    /**
     * @var PredicateDetails
     */
    public $predicateDetails;

    /**
     * @var PredicateEnvRange
     */
    public $predicateEnvRange;

    /**
     * @var PredicateElementType[]
     */
    public $predicateElementType = [];

    /**
     * @var PredicateFreeText
     */
    public $predicateFreeText;

    /**
     * Predicate constructor.
     *
     * @param PredicateOptions $options
     */
    public function __construct(PredicateOptions $options)
    {
        $this->loadDetails($options->details);
        $this->loadTypes($options->types);
        $this->loadRange($options->rangeMin, $options->rangeMax);
        $this->loadFreeText($options->freeText);
    }

    /**
     * @param PredicateDetail[] $details
     * @return void
     */
    protected function loadDetails($details)
    {
        foreach ($details as $key => $detail) {
            if ($key === 0) {
                $this->predicateDetails = new PredicateDetails(
                    $detail->option,
                    $detail->associatedOption
                );
            } else {
                $this->predicateDetails->otherSelectionDetails[] = new PredicateSelectionDetails(
                    $detail->option,
                    $detail->associatedOption
                );
            }
        }
    }

    /**
     * @param PredicateType[] $types
     * @return void
     */
    protected function loadTypes($types)
    {
        foreach ($types as $type) {
            $tmp = new PredicateElementType(
                $type->elementName
            );

            if (!is_null($type->reference) && !is_null($type->referenceQualifier)) {
                $tmp->reference = new Reference(
                    $type->reference,
                    $type->referenceQualifier
                );
            }

            $this->predicateElementType[] = $tmp;
        }
    }

    /**
     * @param int|null $rangeMin
     * @param int|null $rangeMax
     * @return void
     */
    protected function loadRange($rangeMin, $rangeMax)
    {
        if (is_int($rangeMin) || is_int($rangeMax)) {
            $this->predicateEnvRange = new PredicateEnvRange(
                $rangeMin,
                $rangeMax
            );
        }
    }

    /**
     * @param string|null $freeText
     * @return void
     */
    protected function loadFreeText($freeText)
    {
        if (!empty($freeText)) {
            $this->predicateFreeText = new PredicateFreeText($freeText);
        }
    }
}
