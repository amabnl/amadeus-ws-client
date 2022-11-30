<?php

namespace Amadeus\Client\ResponseHandler\Fare;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Session\Handler\SendResult;

class HandlerTLAGetFareRules extends StandardResponseHandler
{
    public function analyze(SendResult $response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }
}
