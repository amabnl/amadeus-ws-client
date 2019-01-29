<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 13/09/2018
 * Time: 10:09
 */

namespace Amadeus\Client\RequestOptions;
use Amadeus\Client\RequestOptions;

class ProfileUpdateProfileOptions extends Base
{
    public $Position;
    //public $Root;
    public $UniqueID=[];
    public $Version;
    /**
     * Construct Update profile with initialization array
     *
     * @param array $params Initialization parameters
     */
    public function __construct($params = [])
    {
        foreach ($params as $propName => $propValue) {
            if (property_exists($this, $propName)) {
                $this->$propName = $propValue;
            }
        }
    }
}