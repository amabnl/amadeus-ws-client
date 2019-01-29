<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 29/10/2018
 * Time: 15:35
 */

namespace Amadeus\Client\RequestOptions;


class ServiceBookPriceProductOptions extends Base
{
    public $Version;
    public $Recommendation;
    //public $ServiceMiscellaneous;
    //public $Products;

    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
            }
        }
    }
}