<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 15/10/2018
 * Time: 09:24
 */

namespace Amadeus\Client\RequestOptions;
use Amadeus\Client\RequestOptions;

class PnrCreateTsmOptions extends Base
{

    public $msg;
    public $mcoData;

    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
            }
        }
    }
}