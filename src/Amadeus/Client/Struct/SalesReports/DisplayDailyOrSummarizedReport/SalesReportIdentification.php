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

namespace Amadeus\Client\Struct\SalesReports\DisplayDailyOrSummarizedReport;

/**
 * SalesReportIdentification
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayDailyOrSummarizedReport
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class SalesReportIdentification
{
    /**
     * A number allocated to a group or an item.
     *
     * @var ItemNumberDetails[]
     */
    public $itemNumberDetails;

    /**
     * SalesReportIdentification constructor.
     *
     * @param $number
     * @param $type
     */
    public function __construct($number, $type)
    {
        $this->itemNumberDetails[] = new ItemNumberDetails($number, $type);
    }
}
