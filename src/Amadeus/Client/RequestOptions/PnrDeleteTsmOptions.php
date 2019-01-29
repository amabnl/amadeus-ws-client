<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 14/11/2018
 * Time: 10:54
 */

namespace Amadeus\Client\RequestOptions;

use Amadeus\Client\RequestOptions;

class PnrDeleteTsmOptions extends Base
{
    public $criteriaTattoo = [];

    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
            }
        }
    }
}