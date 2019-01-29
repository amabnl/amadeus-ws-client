<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 12/09/2018
 * Time: 14:15
 */

namespace Amadeus\Client\Struct\Profile;
use Amadeus\Client\Struct\BaseWsMessage;

class ProfileDeleteProfile extends BaseWsMessage
{
    public $Version;
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