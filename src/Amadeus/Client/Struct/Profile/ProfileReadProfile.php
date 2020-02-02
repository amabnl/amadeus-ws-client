<?php

namespace Amadeus\Client\Struct\Profile;
use Amadeus\Client\Struct\BaseWsMessage;
class ProfileReadProfile extends BaseWsMessage
{
    public $Version;

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