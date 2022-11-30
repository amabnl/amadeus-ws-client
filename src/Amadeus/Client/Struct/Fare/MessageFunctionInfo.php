<?php

namespace Amadeus\Client\Struct\Fare;

class MessageFunctionInfo
{
    /**
     * @var MessageFunctionDetails
     */
    public $messageFunctionDetails;

    /**
     * Create msgType
     *
     * @param string|null $messageFunction
     */
    public function __construct($messageFunction = null)
    {
        $this->messageFunctionDetails = new MessageFunctionDetails($messageFunction);
    }
}
