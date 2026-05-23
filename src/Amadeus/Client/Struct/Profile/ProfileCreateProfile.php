<?php

namespace Amadeus\Client\Struct\Profile;

class ProfileCreateProfile extends BaseProfileMessage
{
    public $UniqueID;
    public $Profile;
    public $CompanyName;

    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->Version= $options->Version;
            $this->UniqueID= $options->UniqueID;
            $this->Profile= $options->Profile;
            $this->CompanyName= $options->CompanyName;
        }
    }
}
