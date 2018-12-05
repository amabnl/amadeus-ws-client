<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 04/12/2018
 * Time: 16:59
 */

namespace Amadeus\Client\RequestOptions;


class PAYGetVirtualCreditCardOptions extends Base
{
    public $transactionContext;
    public $paymentAndPnrContext;
}