<?php

namespace Amadeus\Client\Struct\Profile;
use Amadeus\Client\Struct\BaseWsMessage;

class ProfileCreateProfile extends BaseWsMessage
{
    public $Version;
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