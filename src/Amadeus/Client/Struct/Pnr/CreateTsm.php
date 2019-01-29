<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 15/10/2018
 * Time: 09:26
 */
namespace Amadeus\Client\Struct\Pnr;
use Amadeus\Client\Struct\BaseWsMessage;

class CreateTsm extends BaseWsMessage
{

    public $msg;
    public $mcoData;

    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->msg = $options->msg;
            $this->mcoData = $options->mcoData;
        }
    }
}