<?php

namespace Amadeus\Client\Struct\Travel\OrderReshop;

/**
 * MarketingCarrierInfo
 *
 * The commercial designation under which a dated operating segment can be
 * booked (DatedMarketingSegmentType).
 *
 * @package Amadeus\Client\Struct\Travel\OrderReshop
 */
class MarketingCarrierInfo
{
    /**
     * @var string
     */
    public $CarrierDesigCode;

    /**
     * @var string
     */
    public $MarketingCarrierFlightNumberText;

    /**
     * @var string|null
     */
    public $RBD_Code;

    /**
     * @param string $carrierDesigCode
     * @param string $marketingCarrierFlightNumberText
     * @param string|null $rbdCode
     */
    public function __construct($carrierDesigCode, $marketingCarrierFlightNumberText, $rbdCode = null)
    {
        $this->CarrierDesigCode = $carrierDesigCode;
        $this->MarketingCarrierFlightNumberText = $marketingCarrierFlightNumberText;

        if ($rbdCode !== null) {
            $this->RBD_Code = $rbdCode;
        }
    }
}
