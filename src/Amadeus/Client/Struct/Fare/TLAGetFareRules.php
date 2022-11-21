<?php

namespace Amadeus\Client\Struct\Fare;

use Amadeus\Client\RequestOptions\FareTLAGetFareRulesOptions;
use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\Struct\Fare\TLAGetFareRules\FareBasisInfo;

class TLAGetFareRules extends BaseWsMessage
{
    public $messageFunctionInfo;

    public $fareBasisInfo;

    /**
     * @param FareTLAGetFareRulesOptions $options
     */
    public function __construct(FareTLAGetFareRulesOptions $options)
    {
        $this->messageFunctionInfo = new MessageFunctionInfo(MessageFunctionDetails::FARE_DISPLAY_RULES);
        $this->loadFareBasisInfo($options);
    }

    private function loadFareBasisInfo(FareTLAGetFareRulesOptions $options)
    {
        $this->fareBasisInfo = new FareBasisInfo($options->tariffClassId, $options->airlineCode);
    }
}
