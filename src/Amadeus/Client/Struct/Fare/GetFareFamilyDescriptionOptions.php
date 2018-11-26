<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 26/11/2018
 * Time: 09:24
 */

namespace Amadeus\Client\Struct\Fare;
use Amadeus\Client\Struct\BaseWsMessage;


class GetFareFamilyDescriptionOptions extends BaseWsMessage
{
    public $standaloneDescriptionRequest=[];

    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->standaloneDescriptionRequest = $options->standaloneDescriptionRequest;
        }
    }
}