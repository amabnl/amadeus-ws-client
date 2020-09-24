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


namespace Amadeus\Client\Struct\MiniRule;


use Amadeus\Client\RequestOptions\MiniRuleGetFromRecOptions;

/**
 * MiniRule GetFromRec
 * @package Amadeus\Client\Struct\MiniRule
 * @author Aleksandr Kalugin <xkalugin@gmail.com>
 */
class GetFromRec
{
    /**
     * @var Language|null
     */
    public $language = null;
    /**
     * @var array
     */
    public $groupRecords = [];

    /**
     * GetFromRec constructor.
     * @param MiniRuleGetFromRecOptions $options
     */
    public function __construct(MiniRuleGetFromRecOptions $options)
    {
        $this->language = new Language($options->language->qualifier, $options->language->code);

        $this->groupRecords['recordID'] = new RecordId($options->pricing->id, $options->pricing->type);

        foreach ($options->filteringSelections as $filteringSelection) {
            $this->groupRecords['filteringSelection']['referenceDetails'][] = new ReferenceDetails($filteringSelection->type, $filteringSelection->value);
        }
    }
}