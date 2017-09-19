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

namespace Amadeus\Client\Struct\SalesReports\DisplayQueryReport;

/**
 * SourceType
 *
 * @package Amadeus\Client\Struct\SalesReports\DisplayQueryReport
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class SourceType
{
    /**
     * Reporting Office
     */
    const AGENCY_SRC_REPORTING_OFFICE = "REP";
    /**
     * STP office
     */
    const AGENCY_SRC_STP_OFFICE = "STP";
    /**
     * TDO office
     */
    const AGENCY_SRC_TDO_OFFICE = "TDO";


    /**
     * self::AGENCY_SRC_*
     *
     * @var string
     */
    public $sourceQualifier1;

    /**
     * @var string
     */
    public $sourceQualifier2;

    /**
     * SourceType constructor.
     *
     * @param string $sourceType self::AGENCY_SRC_*
     */
    public function __construct($sourceType)
    {
        $this->sourceQualifier1 = $sourceType;
    }
}
