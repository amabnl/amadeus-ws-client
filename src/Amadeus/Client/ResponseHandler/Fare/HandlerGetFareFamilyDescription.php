<?php

namespace Amadeus\Client\ResponseHandler\Fare;

use Amadeus\Client\Exception;
use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * Class HandlerGetFareFamilyDescription
 * @package Amadeus\Client\ResponseHandler\Fare
 * @author Valerii Nezhurov <valeriy.nezhuriov@gmail.com>
 */
class HandlerGetFareFamilyDescription extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     * @throws Exception
     */
    public function analyze(SendResult $response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessage($response);
    }
}
