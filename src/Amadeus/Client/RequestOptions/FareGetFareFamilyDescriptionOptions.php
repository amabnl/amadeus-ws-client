<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 26/11/2018
 * Time: 09:19
 */

namespace Amadeus\Client\RequestOptions;
use Amadeus\Client\RequestOptions;

class FareGetFareFamilyDescriptionOptions extends Base
{
    public $standaloneDescriptionRequest=[];

    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
            }
        }
    }
}