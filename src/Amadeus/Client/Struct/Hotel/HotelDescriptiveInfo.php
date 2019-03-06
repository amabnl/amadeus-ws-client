<?php
/**
 * Created by PhpStorm.
 * User: backend
 * Date: 20/02/2019
 * Time: 13:18
 */

namespace Amadeus\Client\Struct\Hotel;

use Amadeus\Client\Struct\BaseWsMessage;
use Amadeus\Client\RequestOptions\HotelDescriptiveInfoOptions;

class HotelDescriptiveInfo extends BaseWsMessage
{
    public $HotelDescriptiveInfos;
    public $EchoToken;
    public $Version;
    public $PrimaryLangID;
    public $xmlns;

    public function __construct(HotelDescriptiveInfoOptions $options)
    {
        $this->HotelDescriptiveInfos = $options->HotelDescriptiveInfos;
        $this->EchoToken = $options->EchoToken;
        $this->Version = $options->Version;
        $this->PrimaryLangID = $options->PrimaryLangID;
        $this->xmlns = $options->xmlns;
    }
}