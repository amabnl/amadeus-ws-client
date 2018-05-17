<?php

namespace Amadeus\Client\ResponseHandler\Ticket;

use Amadeus\Client\ResponseHandler\StandardResponseHandler;
use Amadeus\Client\Result;
use Amadeus\Client\Session\Handler\SendResult;

/**
 * HandlerCreateTASF
 *
 * @package Amadeus\Client\ResponseHandler\Ticket
 * @author Artem Zakharchenko <artz.relax@gmail.com>
 */
class HandlerCreateTASF extends StandardResponseHandler
{
    /**
     * @param SendResult $response
     * @return Result
     */
    public function analyze(SendResult $response)
    {
        return $this->analyzeSimpleResponseErrorCodeAndMessageStatusCode($response);
    }
}
