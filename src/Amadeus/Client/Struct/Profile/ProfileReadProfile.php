<?php

namespace Amadeus\Client\Struct\Profile;

class ProfileReadProfile extends BaseProfileMessage
{
    public $UniqueID;
    public $ReadRequests;

    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->Version= $options->Version;
            $this->UniqueID= $options->UniqueID;
            $this->ReadRequests= $options->ReadRequests;
        }
    }
}
