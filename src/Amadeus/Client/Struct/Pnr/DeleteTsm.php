<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 14/11/2018
 * Time: 10:56
 */

namespace Amadeus\Client\Struct\Pnr;
use Amadeus\Client\Struct\BaseWsMessage;

class DeleteTsm extends BaseWsMessage
{
    public $criteriaTattoo = [];

    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->criteriaTattoo = $options->criteriaTattoo;
        }
    }
}