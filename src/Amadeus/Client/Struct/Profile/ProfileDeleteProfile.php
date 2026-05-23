<?php

namespace Amadeus\Client\Struct\Profile;

class ProfileDeleteProfile extends BaseProfileMessage
{
    public $UniqueID;
    public $DeleteRequests;

    public function __construct($options)
    {
        if (!is_null($options)) {
            $this->Version= $options->Version;
            $this->UniqueID= $options->UniqueID;
            $this->DeleteRequests= $options->DeleteRequests;
        }
    }
}
