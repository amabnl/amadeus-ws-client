<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 22/02/2019
 * Time: 10:31
 */

namespace Amadeus\Client\RequestOptions;


class HotelEnhancedPricingOptions extends Base
{
    public $xsi = 'http://www.w3.org/2001/XMLSchema-instance';
    public $EchoToken = 'Pricing';
//    public $SummaryOnly = false;
//    public $AvailRatesOnly = true;
    public $PrimaryLangID = 'EN';
//    public $RateRangeOnly = false;
//    public $RequestedCurrency = 'EUR';
    public $Version = '4.000';
    public $AvailRequestSegments;
}