<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 04/12/2018
 * Time: 17:02
 */
namespace Amadeus\Client\Struct\PAY;
use Amadeus\Client\RequestOptions\PAYGetVirtualCreditCardOptions;
use Amadeus\Client\Struct\BaseWsMessage;

class PAYGetVirtualCreditCard extends BaseWsMessage
{
    public $transactionContext;
    public $paymentAndPnrContext;

    public function __construct($requestOptions)
    {
        if (!is_null($requestOptions)) {
            $this->transactionContext = $requestOptions->transactionContext;
            $this->paymentAndPnrContext = $requestOptions->paymentAndPnrContext;
        }
    }
}