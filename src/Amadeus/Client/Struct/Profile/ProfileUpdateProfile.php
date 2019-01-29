<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 13/09/2018
 * Time: 10:10
 */

namespace Amadeus\Client\Struct\Profile;
use Amadeus\Client\Struct\BaseWsMessage;

class ProfileUpdateProfile extends BaseWsMessage
{
    public $Position;
    //public $Root;
    public $UniqueID=[];
    public $Version;

    public function __construct($options)
    {
        if (!is_null($options)) {
            foreach ($options as $propName => $propValue) {
                if (property_exists($this, $propName)) {
                    $this->$propName = $propValue;
                }
            }
        }
    }
}