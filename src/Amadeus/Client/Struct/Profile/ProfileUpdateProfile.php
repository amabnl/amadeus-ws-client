<?php

namespace Amadeus\Client\Struct\Profile;

class ProfileUpdateProfile extends BaseProfileMessage
{
    public $Position;
    //public $Root;
    public $UniqueID = [];

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
