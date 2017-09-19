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

namespace Amadeus\Client\Struct\Fop;

use Amadeus\Client\RequestOptions\Fop\InstallmentsInfo;

/**
 * ExtendedPaymentDetails
 *
 * @package Amadeus\Client\Struct\Fop
 * @author Dieter Devlieghere <dermikagh@gmail.com>
 */
class ExtendedPaymentDetails
{
    const FORMAT_YYMMDD = 101;
    const FORMAT_YYDDD = 105;
    const FORMAT_MMDD = 106;

    /**
     * @var int|string
     */
    public $instalmentsNumber;

    /**
     * @var string
     */
    public $instalmentsFrequency;

    /**
     * @var string
     */
    public $instalmentsStartDate;

    /**
     * 10 CCYYMMDDTHHMM
     * 101 YYMMDD
     * 102 CCYYMMDD
     * 103 YYWWD
     * 105 YYDDD
     * 106 MMDD
     * 107 DDD
     * 108 WW
     * 109 MM
     * 110 DD
     * 2 DDMMYY
     * 201 YYMMDDHHMM
     * 202 YYMMDDHHMMSS
     * 203 CCYYMMDDHHMM
     * 204 CCYYMMDDHHMMSS
     * 205 CCYYMMDDHHMMZHHMM
     * 3 MMDDYY
     * 301 YYMMDDHHMMZZZ
     * 302 YYMMDDHHMMSSZZZ
     * 303 CCYYMMDDHHMMZZZ
     * 304 CCYYMMDDHHMMSSZZZ
     * 305 MMDDHHMM
     * 306 DDHHMM
     * 4 DDMMCCYY
     * 401 HHMM
     * 402 HHMMSS
     * 404 HHMMSSZZZ
     * 405 MMMMSS
     * 406 ZHHMM
     * 5 DDMMCCYYHHMM
     * 501 HHMMHHMM
     * 502 HHMMSS-HHMMSS
     * 503 HHMMSSZZZ-HHMMSSZZZ
     * 6 CCYYMMB
     * 600 CC
     * 601 YY
     * 602 CCYY
     * 603 YYS
     * 604 CCYYS
     * 608 CCYYQ
     * 609 YYMM
     * 610 CCYYMM
     * 613 YYMMA
     * 614 CCYYMMA
     * 615 YYWW
     * 616 CCYYWW
     * 7 CCYYMMW
     * 701 YY-YY
     * 702 CCYY-CCYY
     * 703 YYS-YYS
     * 704 CCYYS-CCYYS
     * 705 YYPYYP
     * 706 CCYYP-CCYYP
     * 707 YYQ-YYQ
     * 708 CCYYQ-CCYYQ
     * 709 YYMM-YYMM
     * 710 CCYYMM-CCYYMM
     * 711 CCYYMMDD-CCYYMMDD
     * 713 YYMMDDHHMM-YYMMDDHHMM
     * 715 YYWW-YYWW
     * 716 CCYYWW-CCYYWW
     * 717 YYMMDD-YYMMDD
     * 718 CCYYMMDD-CCYYMMDD
     * 719 CCYYMMDDHHMM-CCYYMMDDHHMM
     * 720 DHHMM-DHHMM
     * 8 CCYYMMDDS
     * 801 Year
     * 802 Month
     * 803 Week
     * 804 Day
     * 805 Hour
     * 806 Minute
     * 807 Second
     * 808 Semester
     * 809 Four months period
     * 810 Trimester
     * 811 Half month
     * 812 Ten days
     * 813 Day of the week
     * 814 Working days
     * 9 CCYYMMDDPP
     *
     * @var int|string
     */
    public $instalmentsDatrDateFormat;

    /**
     * ExtendedPaymentDetails constructor.
     *
     * @param InstallmentsInfo $info
     */
    public function __construct(InstallmentsInfo $info)
    {
        $this->instalmentsNumber = $info->nrOfInstallments;
        $this->instalmentsFrequency = $info->frequency;
        $this->instalmentsDatrDateFormat = $info->format;
        $this->instalmentsStartDate = $this->convertDate($info->startDate, $info->format);
    }

    /**
     * @param \DateTime $startDate
     * @param string|int $format
     * @return string
     * @throws \RuntimeException
     */
    protected function convertDate($startDate, $format)
    {
        $date = "";

        if ($startDate instanceof \DateTime) {
            switch ($format) {
                case self::FORMAT_YYMMDD:
                    $date = $startDate->format('ymd');
                    break;
                case self::FORMAT_MMDD:
                    $date = $startDate->format('md');
                    break;
                case self::FORMAT_YYDDD:
                    $date = $startDate->format('yz');
                    if (strlen($date) === 4) {
                        $date = substr($date, 0, 2)."0".substr($date, 2);
                    }
                    break;
                default:
                    throw new \RuntimeException("Installments Format '".$format."' is not implemented!");
            }
        }

        return $date;
    }
}
