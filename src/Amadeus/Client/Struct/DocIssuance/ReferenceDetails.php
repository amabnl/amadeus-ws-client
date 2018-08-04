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

/**
 * ReferenceDetails
 *
 * @package Amadeus\Client\Struct\DocIssuance
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ReferenceDetails
{
    const TYPE_TST = "TS";

    const TYPE_SEGMENT_TATTOO = "ST";

    const TYPE_LINE_NUMBER = "L";

    const TYPE_SEGMENT = "SN";

    const TYPE_COUPON_NUMBER = "C";

    const TYPE_PNR_FA_TATTOO = "FAT";

    const TYPE_PNR_FHE_TATTOO = "FHT";

    const TYPE_TSM = "M";

    const TYPE_TSM_TATTOO = "TMT";

    /**
     * self::TYPE_*
     *
     * C   Coupon number selection
     * FAT PNR FA TATOO
     * FHT PNR FHE TATOO
     * L   Line number selection
     * SN  Segment selection
     * ST  Segment Tatoo reference number
     * TS  TST selection
     * M   TSM selection
     * TMT TSM Tatoo
     *
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $value;

    /**
     * ReferenceDetails constructor.
     *
     * @param string $value
     * @param string $type self::TYPE_*
     */
    public function __construct($value, $type)
    {
        $this->value = $value;
        $this->type = $type;
    }
}
