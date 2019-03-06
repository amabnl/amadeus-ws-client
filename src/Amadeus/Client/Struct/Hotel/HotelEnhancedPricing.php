<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 22/02/2019
 * Time: 10:28
 */

namespace Amadeus\Client\Struct\Hotel;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\RequestOptions\HotelEnhancedPricingOptions;

class HotelEnhancedPricing extends BaseWsMessage
{
    public $xsi;
    public $EchoToken;
//    public $SummaryOnly;
//    public $AvailRatesOnly;
    public $PrimaryLangID;
//    public $RateRangeOnly;
//    public $RequestedCurrency;
    public $Version;
    public $AvailRequestSegments;

    public function __construct(HotelEnhancedPricingOptions $options)
    {
        $this->xsi = $options->xsi;
        $this->EchoToken = $options->EchoToken;
//        $this->SummaryOnly = $options->SummaryOnly;
//        $this->AvailRatesOnly = $options->AvailRatesOnly;
        $this->PrimaryLangID = $options->PrimaryLangID;
//        $this->RateRangeOnly = $options->RateRangeOnly;
//        $this->RequestedCurrency = $options->RequestedCurrency;
        $this->Version = $options->Version;
        $this->AvailRequestSegments = $options->AvailRequestSegments;
    }
}